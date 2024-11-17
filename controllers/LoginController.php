<?php


namespace app\controllers;


use app\models\LoginForm;
use app\models\User;
use app\models\RegistrationForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;

class LoginController extends Controller
{
    public $layout = 'login';


    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $loginForm = new LoginForm();

        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {
            return $this->goHome();
        }

        $loginForm->password = '';


        return $this->render('index', [
            'model' => $loginForm,
        ]);
    }

    public function actionRegistration()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegistrationForm();

        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            Yii::$app->session->setFlash('success', 'Вы успешно зарегистрированы.');
            return $this->redirect(['login/index']);
        }

        return $this->render('registration', [
            'model' => $model,
        ]);
    }

    public function actionRepassword()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        //$registerForm = $this->generateForm(['error' => 'Ошибка восстановления пароля!!!']);
        $registerForm = $this->generateFormNew('repass');

        return $this->render('repassword', compact('registerForm'));
    }

    public function actionLogout()
    {
            Yii::$app->user->logout();

        return $this->redirect('/login');
    }

}