<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\BitrixForm $model */

$this->title = 'Создать сделку в Битрикс24';

$script = <<<JS
let fileIndex = 1;
$('#add-file-button').on('click', function(e) {
    e.preventDefault();
    let newFileInput = `
        <div class="file-input-group">
            <input type="file" name="BitrixForm[uf_crm_deal_1691729934958][]" class="form-control mb-2">
            <button type="button" class="btn btn-danger remove-file-button">Удалить</button>
        </div>`;
    $('#file-input-container').append(newFileInput);
});

$('#file-input-container').on('click', '.remove-file-button', function(e) {
    e.preventDefault();
    $(this).closest('.file-input-group').remove();
});
JS;

$this->registerJs($script);
?>

<div class="bitrix-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_id')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'uf_crm_deal_1690807644497')->textInput(['maxlength' => true]) ?>

    <div id="file-input-container">
        <?= $form->field($model, 'uf_crm_deal_1691729934958[]')->fileInput(['multiple' => false]) ?>
    </div>

    <button id="add-file-button" class="btn btn-success">Добавить файл</button>

    <div class="form-group">
        <?= Html::submitButton('Создать сделку', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
