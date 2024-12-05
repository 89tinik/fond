<?php

use app\models\Applications;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\ApplicationsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Заявки';
?>
<div class="applications-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'contest_id',
                'value' => function (Applications $model) {
                    return $model->contest->name;
                },
                'filter' => false,
            ],
            [
                'attribute' => 'user_id',
                'value' => function (Applications $model) {
                    return $model->user->lastname . ' ' . $model->user->firstname . ' ' . $model->user->surname;
                },
                'filter' => false,
            ],
            [
                'attribute' => 'status',
                'filter' => [
                    'draft' => 'Черновик',
                    'send' => 'Отправлено',
                ],
                'value' => function (Applications $model) {
                    return $model->status === 'draft' ? 'Черновик' : ($model->status === 'send' ? 'Отправлено' : $model->status);
                },
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => false,
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
                'filter' => false,
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'urlCreator' => function ($action, Applications $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
            ],
        ],
    ]); ?>

</div>
