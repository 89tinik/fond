<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Личные данные';
?>
<div class="info-block">
    <div class="info-item">
        <span class="info-label">ФИО: </span>
        <?= \Yii::$app->user->identity->lastname .' '.\Yii::$app->user->identity->firstname .' '.\Yii::$app->user->identity->surname ?>
    </div>
    <div class="info-item">
        <span class="info-label">Телефон: </span>
        <?= \Yii::$app->user->identity->phone ?>
    </div>
    <div class="info-item">
        <span class="info-label">Эл. почта: </span>
        <?= \Yii::$app->user->identity->email ?>
    </div>
</div>
