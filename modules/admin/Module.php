<?php

namespace app\modules\admin;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        \Yii::$app->setComponents(
            [
                'user' => [
                    'class' => 'yii\web\User',
                    'identityClass' => 'app\modules\admin\models\Admin',
                    'loginUrl' => ['admin/default/login'],
                    'authTimeout' => 3600,
                    'identityCookie' => ['name' => '_admin_identity', 'httpOnly' => true],
                    'enableAutoLogin' => true,
                ],
            ]
        );
    }
}
