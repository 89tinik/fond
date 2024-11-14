<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\widgets\MaskedInput;

$this->title = 'Авторизация';
?>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'labelOptions' => ['class' => 'form-label'],
        'inputOptions' => ['class' => 'form-control'],
        'errorOptions' => ['class' => 'invalid-feedback'],
    ],
]); ?>

<?= $form->field($model, 'username')->widget(MaskedInput::class, ['mask' => '+7 999 999 99 99'])->textInput() ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<?= Html::submitButton('Войти', ['class' => 'btn btn-primary w-100', 'name' => 'login-button']) ?>

<div class="mt-3 text-center">
    <?= Html::a('Зарегистрироваться', ['login/registration']); ?>
</div>

<?php ActiveForm::end(); ?>
