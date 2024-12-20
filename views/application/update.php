<?php

/** @var yii\web\View $this */
/** @var app\models\Applications $application */
/** @var app\models\ApplicationsForm $formModel */
/** @var app\models\Sections[] $sections */
/** @var app\models\Contests $type */
/** @var array $companies */
/** @var bool $noUpdate */

$this->title = 'Редактирование заявки "' . $type->name . '"';

?>

<?php
echo $this->render('form', [
    'sections' => $sections,
    'formModel' => $formModel,
    'type' => 'update',
    'companies' => $companies,
    'noUpdate' => $noUpdate
]);
?>


