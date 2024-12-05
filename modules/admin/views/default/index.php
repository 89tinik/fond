<?php

use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Пользователи';
?>
<div class="user-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'], // Нумерация строк

            'firstname',
            'surname',
            'lastname',
            'email:email',
            'phone',
            'post',
            'b24Id',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.Y'],
                'filter' => false,
            ],
        ],
    ]); ?>

</div>
