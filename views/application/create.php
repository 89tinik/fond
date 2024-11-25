<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var \app\models\Contests $type */
/** @var yii\web\View $this */
/** @var app\models\ApplicationsForm $formModel */
/** @var app\models\Sections[] $sections */


$this->title = 'Новая заявка "' . $type->name . '"';
?>



<?php
echo $this->render('form', [
    'sections' => $sections,
    'formModel' => $formModel,
    'type' => 'new'
]);
?>