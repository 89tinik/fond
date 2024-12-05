<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Applications $model */

$this->title = 'Заявка #' . $model->id;
?>
<div class="applications-view">



    <h2>Основная информация</h2>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'contest_id',
                'value' => $model->contest ? $model->contest->name : null,
            ],
            [
                'attribute' => 'user_id',
                'value' => $model->user ? $model->user->lastname . ' ' . $model->user->firstname . ' ' . $model->user->surname : null,
            ],
            'status',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <h2>Дополнительные поля</h2>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Название поля</th>
            <th>Значение</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($model->applicationValues as $value): ?>
            <tr>
                <td><?= Html::encode($value->field->label) ?></td>
                <td>
                    <?php
                    switch ($value->field->type) {
                        case 'select':
                            $selectData = json_decode($value->field->options, true);
                            echo $selectData[$value->value];
                            break;
                        case 'file':
                            $filesData = json_decode($value->value, true);
                            foreach ($filesData as $file) {
                                if (!empty($file)) {
                                    echo Html::a(basename($file), ['@web/' . $file], ['target' => '_blank']) . '<br>';
                                }
                            }
                            break;
                        default:
                            if ($value->field->multi) {
                                $dataArr = json_decode($value->value, true);
                                echo '<ul>';
                                foreach ($dataArr as $item) {
                                    echo '<li>' . $item . '</li>';
                                }
                                echo '</ul>';
                            } else {
                                echo Html::encode($value->value);
                            }
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
