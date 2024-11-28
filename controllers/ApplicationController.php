<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\models\Applications;
use app\models\ApplicationsForm;
use app\models\ApplicationValue;
use app\models\Contests;
use app\models\Sections;
use Yii;
use yii\web\Response;
use yii\web\UploadedFile;
use app\models\BitrixForm;

class ApplicationController extends BaseController
{

    /**
     * @return string
     */
    public function actionIndex()
    {
        $draftApplications = Applications::find()->where(['status' => 'draft'])->all();
        $sendApplications = Applications::find()->where(['status' => 'send'])->all();
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

        $sections = Sections::find()
            ->where(['contest_id' => $appType->id])
            ->orderBy(['position' => SORT_ASC])
            ->all();

        $formModel = new ApplicationsForm($type);

        if (Yii::$app->request->isPost) {
            $formData = Yii::$app->request->post('ApplicationsForm');

            if ($formModel->load(Yii::$app->request->post()) && $formModel->validate()) {
                $application = new Applications($appType->id);
                $application->save();
                ApplicationValue::loadFields($formData['fields'], $application->id);

                Yii::$app->session->setFlash('success', 'Форма успешно отправлена!');
                return $this->redirect(['application/update', 'id' => $application->id]);
            }
        }

        return $this->render('create', [
            'sections' => $sections,
            'formModel' => $formModel,
            'type' => $appType
        ]);
    }

    public function actionUpdate($id)
    {
        $application = Applications::findOne($id);

        if (!$application || $application->user_id !== Yii::$app->user->id) {
            throw new \yii\web\NotFoundHttpException('Заявка не найдена или доступ запрещен.');
        }

        $appType = Contests::findOne($application->contest_id);

        $sections = Sections::find()
            ->where(['contest_id' => $appType->id])
            ->orderBy(['position' => SORT_ASC])
            ->all();

        $formModel = new ApplicationsForm($application->contest_id);

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
            'type' => $appType
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
                case 'text':
                    if ($fieldValue->field->multi) {
                        $data[$fieldValue->field->b24entity]['fields'][$fieldValue->field->name] = json_decode($fieldValue->value, 1);
                    } else {
                        $data[$fieldValue->field->b24entity]['fields'][$fieldValue->field->name] = $fieldValue->value;
                    }
                    break;
            }
        }

        $bitrixDealUrl = 'https://historyrussia.bitrix24.ru/rest/100/wmqfhmjhyn27avso/crm.deal.add';
        $bitrixContactUrl = 'https://historyrussia.bitrix24.ru/rest/100/wmqfhmjhyn27avso/crm.contact.add';
        $bitrixCompanyUrl = 'https://historyrussia.bitrix24.ru/rest/100/wmqfhmjhyn27avso/crm.company.add';
        $data['deal']['fields']['CATEGORY_ID'] = $application->contest->typeB24Id;

        $responseContact = $this->sendRequest($bitrixContactUrl, $data['contact']);
        $responseCompany = $this->sendRequest($bitrixCompanyUrl, $data['contact']);

        $data['deal']['fields']['CONTACT_ID'] = $responseContact['result'];
        $data['deal']['fields']['COMPANY_ID'] = $responseCompany['result'];
        
         $response = $this->sendRequest($bitrixDealUrl, $data['deal']);

        if (isset($response['result'])) {
            return 'Сделка успешно создана в Битрикс24.';
        } else {
            return 'Ошибка при создании сделки: ';
        }
    }
    public function actionCreateBitrixOld()
    {

        $model = new BitrixForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->uf_crm_deal_1691729934958 = UploadedFile::getInstances($model, 'uf_crm_deal_1691729934958');

            if ($model->validate()) {
                // Создаем сделку в Битрикс24
                $bitrixUrl = 'https://historyrussia.bitrix24.ru/rest/100/wmqfhmjhyn27avso/crm.deal.add';
                $data = [
                    'fields' => [
                        'TITLE' => $model->title,
                        'CATEGORY_ID' => $model->category_id,
                        'uf_crm_deal_1690807644497' => $model->uf_crm_deal_1690807644497,
                    ],
                ];

                if (!empty($model->uf_crm_deal_1691729934958)) {
                    $filesArray = [];
                    foreach ($model->uf_crm_deal_1691729934958 as $file) {
                        $filesArray[] = [
                            "fileData" => [
                                $file->name,
                                base64_encode(file_get_contents($file->tempName))
                            ]
                        ];
                    }
                    $data['fields']['UF_CRM_DEAL_1691729934958'] = $filesArray;
                }

                $response = $this->sendRequest($bitrixUrl, $data);

                if (isset($response['result'])) {
                    Yii::$app->session->setFlash('success', 'Сделка успешно создана в Битрикс24.');
                    return $this->refresh();
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка при создании сделки: ' . json_encode($response));
                }
            }
        }

        return $this->render('create-bitrix', [
            'model' => $model,
        ]);
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


    private function sendRequest($url, $data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

}