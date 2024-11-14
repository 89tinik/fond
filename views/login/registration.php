<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\widgets\MaskedInput;

/** @var yii\web\View $this */
/** @var app\models\RegistrationForm $model */

$this->title = 'Регистрация';
?>


<?php $form = ActiveForm::begin([
    'id' => 'registrationForm',
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'labelOptions' => ['class' => 'form-label'],
        'inputOptions' => ['class' => 'form-control'],
        'errorOptions' => ['class' => 'invalid-feedback'],
    ],
]); ?>

<?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'email')->input('email') ?>
<?= $form->field($model, 'phone')->widget(MaskedInput::class, ['mask' => '+7 999 999 99 99'])
    ->textInput() ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'password_repeat')->passwordInput() ?>

<div class="form-check mb-3">
    <?= Html::checkbox('RegistrationForm[polit]', false, [
        'class' => 'form-check-input',
        'id' => 'agreeTerms',
        'required' => true
    ]) ?>
    <label class="form-check-label" for="agreeTerms">
        Согласен с <?= Html::a('политикой обработки персональных данных', ['polit'], ['target' => '_blank']) ?>
    </label>
</div>


<?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary w-100', 'id' => 'registerBtn']) ?>
<div class="mt-3 text-center">
    <?= Html::a('Есть аккаунт? Войти', ['login/index']) ?>
</div>


<?php ActiveForm::end(); ?>
