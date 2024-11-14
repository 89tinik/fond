<?php

/** @var yii\web\View $this */

use app\models\ApplicattionTypes;
use yii\bootstrap5\Html;

$this->title = 'Разделы';

foreach (ApplicattionTypes::$typeArr as $type => $typeName) {

    echo Html::a($typeName, ['application/create', 'type' => $type], ['class' => 'block-link']);
}
