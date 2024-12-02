<?php

/** @var yii\web\View $this */
/** @var string $files */
/** @var int $fieldId */
/** @var bool $noUpdate */


if ($files) {
    $filesArr = json_decode($files);
    foreach ($filesArr as $idx => $file):
        if (!empty($file)): ?>
            <div class="file-item d-flex align-items-center mb-1">
                <a href="/<?= $file ?>" class="me-2" target="_blank"><?= basename($file) ?></a>
                <?php if (!$noUpdate) : ?>
                    <button type="button" class="btn-close" aria-label="Удалить"
                            onclick="removeUploadedFile(this,'<?= $idx ?>','<?= Yii::$app->request->get('id') ?>','<?= $fieldId ?>',)"></button>
                <?php endif; ?>
            </div>
        <?php endif;
    endforeach;
}