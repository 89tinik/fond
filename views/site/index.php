<?php

/** @var yii\web\View $this */
/** @var array $typeArr */

use yii\bootstrap5\Html;

$this->title = 'Разделы';

foreach ($typeArr as $type) {
    echo Html::a($type['name'], ['application/create', 'type' => $type['id']], ['class' => 'block-link']);
}
