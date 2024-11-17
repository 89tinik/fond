<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\models\Applications;
use app\models\ApplicationsForm;
use app\models\ApplicationValue;
use app\models\Contests;
use app\models\Sections;
use Yii;
use yii\web\NotFoundHttpException;

class ApplicationController extends BaseController
{

    /**
     * @return string
     */
    public function actionIndex()
    {
        $draftApplications = Applications::find()->where(['status'=>'draft'])->all();
        $sendApplications = Applications::find()->where(['status'=>'send'])->all();
        return $this->render('index',[
            'draftApplications' => $draftApplications,
            'sendApplications' => $sendApplications
        ]);
    }

    /**
     * @return string
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
                $application = new Applications();
                $application->contest_id = $appType->id;
                $application->user_id = Yii::$app->user->id;
                $application->save();

                foreach ($formData['fields'] as $fieldId => $value) {
                    $applicationValue = new ApplicationValue();
                    $applicationValue->field_id = $fieldId;
                    $applicationValue->value = $value;
                    $applicationValue->application_id = $application->id;
                    $applicationValue->save();
                }

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
                foreach ($formData['fields'] as $fieldId => $value) {
                    if (isset($existingValues[$fieldId])) {
                        $applicationValue = $existingValues[$fieldId];
                    } else {
                        $applicationValue = new ApplicationValue();
                        $applicationValue->field_id = $fieldId;
                        $applicationValue->application_id = $application->id;
                    }
                    $applicationValue->value = $value;
                    $applicationValue->save();
                }

                Yii::$app->session->setFlash('success', 'Заявка успешно обновлена!');
                return $this->refresh();
            }
        }

        return $this->render('update', [
            'sections' => $sections,
            'formModel' => $formModel,
            'type' => $appType
        ]);
    }
}