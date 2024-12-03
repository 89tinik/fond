<?php

namespace app\controllers;

use app\models\Contests;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends BaseController
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $typeArr = Contests::find()->asArray()->all();
        return $this->render('index', [
            'typeArr' => $typeArr,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {

        return $this->render('contact');
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionLk()
    {
        return $this->render('lk');
    }
}
