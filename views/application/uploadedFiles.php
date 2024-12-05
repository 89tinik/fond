<?php

/** @var yii\web\View $this */
/** @var string $files */
/** @var int $fieldId */
/** @var bool $noUpdate */


use yii\bootstrap5\Html;

if ($files) {
    $filesArr = json_decode($files);
    foreach ($filesArr as $idx => $file):
        if (!empty($file)): ?>
            <div class="file-item d-flex align-items-center mb-1">
                <?= Html::a(basename($file), ['@web/' . $file], ['target' => '_blank', 'class'=>'class'])?>
                <?php if (!$noUpdate) : ?>
                    <button type="button" class="btn-close" aria-label="Удалить"
                            onclick="removeUploadedFile(this,'<?= $idx ?>','<?= Yii::$app->request->get('id') ?>','<?= $fieldId ?>',)"></button>
                <?php endif; ?>
            </div>
        <?php endif;
    endforeach;
}