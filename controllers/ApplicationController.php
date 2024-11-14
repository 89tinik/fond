<?php

namespace app\controllers;

use app\controllers\BaseController;

class ApplicationController extends BaseController
{

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionCreate($type)
    {
        return $this->render('create', ['type' => $type]);
    }
}