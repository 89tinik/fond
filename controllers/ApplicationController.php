<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\models\Applications;
use app\models\ApplicationsForm;
use app\models\ApplicationValue;
use app\models\B24Data;
use app\models\Companies;
use app\models\Contests;
use app\models\Sections;
use app\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use app\models\BitrixForm;

class ApplicationController extends BaseController
{

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }


    /**
     * @return string
     */
    public function actionIndex()
    {
        $draftApplications = Applications::find()->where(['status' => 'draft', 'user_id' => Yii::$app->user->id])->all();
        $sendApplications = Applications::find()->where(['status' => 'send', 'user_id' => Yii::$app->user->id])->all();
        return $this->render('index', [
            'draftApplications' => $draftApplications,
            'sendApplications' => $sendApplications
        ]);
    }

    /**
     * @return Response | string
     */
    public function actionCreate($type)
    {
        $appType = Contests::findOne($type);
        $userCompanies = Companies::getCompaniesList();

        $sections = Sections::find()
            ->where(['contest_id' => $appType->id])
            ->orderBy(['position' => SORT_ASC])
            ->all();

        $formModel = new ApplicationsForm($type);

        if (Yii::$app->request->isPost) {
            $formData = Yii::$app->request->post('ApplicationsForm');

            if ($formModel->load(Yii::$app->request->post()) && $formModel->validate()) {
                $application = new Applications($appType->id);
                if (array_key_exists('companyId', $formData)) {
                    $application->company_id = $formData['companyId'];
                }
                $application->save();

                ApplicationValue::loadFields($formData['fields'], $application->id);

                //Yii::$app->session->setFlash('success', 'Форма успешно отправлена!');
                if ($formData['sendB24']) {
                    return $this->redirect(['application/create-bitrix', 'id' => $application->id]);
                }
                return $this->redirect(['application/update', 'id' => $application->id]);
            }
        }

        return $this->render('create', [
            'sections' => $sections,
            'formModel' => $formModel,
            'type' => $appType,
            'companies' => $userCompanies
        ]);
    }

    public function actionUpdate($id)
    {
        $application = Applications::findOne($id);
        $userCompanies = Companies::getCompaniesList();

        if (!$application || $application->user_id !== Yii::$app->user->id) {
            throw new \yii\web\NotFoundHttpException('Заявка не найдена или доступ запрещен.');
        }

        $appType = Contests::findOne($application->contest_id);

        $sections = Sections::find()
            ->where(['contest_id' => $appType->id])
            ->orderBy(['position' => SORT_ASC])
            ->all();

        $formModel = new ApplicationsForm($application->contest_id, $application->company_id);

        $existingValues = ApplicationValue::find()
            ->where(['application_id' => $application->id])
            ->indexBy('field_id')
            ->all();

        $formData = [];
        foreach ($existingValues as $value) {
            $formData['fields'][$value->field_id] = $value->value;
        }
        $formModel->fields = $formData['fields'];

        if (Yii::$app->request->isPost) {
            $formData = Yii::$app->request->post('ApplicationsForm');
            if (array_key_exists('companyId', $formData)) {
                $application->company_id = $formData['companyId'];
            }
            $application->save();
            if ($formModel->load(Yii::$app->request->post()) && $formModel->validate()) {
                if (ApplicationValue::loadFields($formData['fields'], $application->id, $existingValues)) {
                    if ($formData['sendB24']) {
                        return $this->redirect(['application/create-bitrix', 'id' => $id]);
                    }
                    Yii::$app->session->setFlash('success', 'Заявка успешно обновлена!');
                    return $this->refresh();
                }
            }
        }

        return $this->render('update', [
            'sections' => $sections,
            'formModel' => $formModel,
            'type' => $appType,
            'companies' => $userCompanies,
            'noUpdate' => $application->status == 'send'
        ]);
    }

    public function actionCreateBitrix($id)
    {
        $application = Applications::findOne($id);


        $existingValues = ApplicationValue::find()
            ->where(['application_id' => $application->id])
            ->indexBy('field_id')
            ->all();

        $data = [];
        foreach ($existingValues as $fieldValue) {
            switch ($fieldValue->field->type) {
                case 'file':
                    if ($fieldValue->field->multi) {
                        $filesArrValue = json_decode($fieldValue->value, true);
                        $filesArray = [];
                        foreach ($filesArrValue as $file) {
                            if ($file) {
                                $filesArray[] = [
                                    "fileData" => [
                                        basename($file),
                                        base64_encode(file_get_contents($file))
                                    ]
                                ];
                            }
                        }
                        if (!empty($filesArray)) {
                            $data[$fieldValue->field->b24entity]['fields'][$fieldValue->field->name] = $filesArray;
                        }
                    }
                    break;
                default :
                    if ($fieldValue->field->multi) {
                        $data[$fieldValue->field->b24entity]['fields'][$fieldValue->field->name] = json_decode($fieldValue->value, 1);
                    } else {
                        if (in_array($fieldValue->field->name, ['PHONE', 'EMAIL'])) {
                            $data[$fieldValue->field->b24entity]['fields'][$fieldValue->field->name] = [
                                ['VALUE' => $fieldValue->value, 'VALUE_TYPE' => 'WORK']
                            ];
                        } else {
                            $data[$fieldValue->field->b24entity]['fields'][$fieldValue->field->name] = $fieldValue->value;
                        }
                    }
                    break;
            }
        }

        $data['deal']['fields']['CATEGORY_ID'] = $application->contest->typeB24Id;

        $b24 = new B24Data();
        $data['deal']['fields']['CONTACT_ID'] = Yii::$app->user->identity->b24Id ?? $b24->checkContact($data['contact']);
        $data['deal']['fields']['COMPANY_ID'] = $application->company->b24Id ?? $b24->checkCompany($data['company'], $existingValues, $application);

        $response = $b24->sendRequest('new-deal', $data['deal']);

        if (isset($response['result'])) {
            $application->status = 'send';
            $application->save();
            return $this->redirect(['application/index']);
        } else {
            $responseP = print_r($response, 1);
            return 'Ошибка при создании сделки: ' . $responseP;
        }
    }

    public function actionUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $outputArr = [];
        if (!Yii::$app->request->isPost) {
            return ['error' => 'Неверный метод запроса'];
        }
        $app = Yii::$app->request->post('app');
        if (empty($app)) {
            return ['error' => 'Параметр "app" обязателен'];
        } elseif ($app == 'new') {
            $contest = Yii::$app->request->post('contest');
            if (empty($contest)) {
                return ['error' => 'Параметр "contest" обязателен'];
            }
            $application = new Applications($contest);
            $application->save();
            $app = $application->id;
            $outputArr['newUrl'] = '/application/update?id=' . $app;
        }

        $uploadedFiles = UploadedFile::getInstancesByName('files');
        if (empty($uploadedFiles)) {
            return ['error' => 'Файлы не переданы'];
        }

        $filesNamesArr = Yii::$app->request->post('files');

        return array_merge($outputArr, ApplicationValue::uploadFiles($uploadedFiles, $filesNamesArr, $app));

    }

    public function actionDeleteFile()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->request->isPost) {
            return ['error' => 'Неверный метод запроса'];
        }

        $requiredParams = ['idx', 'app', 'field'];
        foreach ($requiredParams as $param) {
            if (empty(Yii::$app->request->post($param))) {
                return ['error' => "Параметр \"$param\" обязателен"];
            }
        }

        return ApplicationValue::deleteFile(
            Yii::$app->request->post('idx'),
            Yii::$app->request->post('app'),
            Yii::$app->request->post('field')
        );
    }


    public function actionGetCompany()
    {

        $b24 = new B24Data();
        $company = Companies::findOne(Yii::$app->request->post('id'));
        return $b24->getCompanyData($company->b24Id);
    }

    /**
     * Deletes an existing Applications model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Applications::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
        $model->delete();

        return $this->redirect(['index']);
    }
}