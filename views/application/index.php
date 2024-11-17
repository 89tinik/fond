<?php

/** @var yii\web\View $this */
/** @var app\models\Applications[] $draftApplications */
/** @var app\models\Applications[] $sendApplications */

use yii\helpers\Url;

$this->title = 'Мои заявки';
?>
<?php if (!empty($draftApplications)): ?>
    <h4 class="section-title">Черновики</h4>
    <?php foreach ($draftApplications as $dApp) : ?>
    <?php $url = Url::to(['application/update', 'id' => $dApp->id])?>
        <div class="application-block" onclick="location.href='<?= $url ?>';">
            <div class="application-info">
                <h6><?= $dApp->contest->name ?> | <?= $dApp->id ?></h6>
                <span>Дата изменения: <?= date('d.m.Y', strtotime($dApp->updated_at)) ?></span>
            </div>
            <button class="delete-btn">Удалить</button>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($sendApplications)): ?>
    <h4 class="section-title">Отправленные заявки</h4>

    <?php foreach ($draftApplications as $dApp) : ?>
        <?php $url = Url::to(['application/view', 'id' => $dApp->id])?>
        <div class="application-block" onclick="location.href='<?=$url?>';">
            <div class="application-info">
                <h6><?= $dApp->contest->name ?></h6>
                <span>Дата отправки: <?= date('d.m.Y', strtotime($dApp->updated_at)) ?></span>
            </div>
        </div>

    <?php endforeach; ?>
<?php endif; ?>