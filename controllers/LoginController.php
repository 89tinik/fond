<?php


namespace app\controllers;


use app\models\LoginForm;
use app\models\ResetPasswordForm;
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

        $model = new ResetPasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $email = $model->email;

            // Поиск пользователя по email
            $user = User::findOne(['email' => $email]);

            if ($user !== null) {
                // Генерация случайного пароля
                $newPassword = Yii::$app->security->generateRandomString(6);

                // Установка нового пароля
                $user->password_hash = Yii::$app->security->generatePasswordHash($newPassword);

                if ($user->save()) {
                    // Отправка email с новым паролем
                    Yii::$app->mailer->compose()
                        ->setTo($email)
                        ->setFrom('no-reply@fond.com')
                        ->setSubject('Ваш новый пароль')
                        ->setTextBody("Ваш новый пароль: $newPassword")
                        ->send();

                    Yii::$app->session->setFlash('success', 'Пароль успешно сброшен и отправлен на email.');
                    return $this->redirect(['/']);
                } else {
                    Yii::$app->session->setFlash('error', 'Не удалось сохранить новый пароль. Попробуйте снова.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Пользователь с таким email не найден.');
            }
        }

        return $this->render('repassword', [
            'model' => $model,
        ]);

    }

    public function actionLogout()
    {
            Yii::$app->user->logout();

        return $this->redirect('/login');
    }

}