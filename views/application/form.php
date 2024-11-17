<?php


use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\ApplicationsForm $formModel */
/** @var app\models\Sections[] $sections */
/** @var string $type */

$navigation = '';
$formFields = '';
?>
<?php $form = ActiveForm::begin(['id' => 'application-form']); ?>

<?php
$sectionNumber = 1;
$class = 'active';
foreach ($sections as $section) {
    $navigation .= '<button class="btn btn-secondary" onclick="showSection(' . $sectionNumber . ')" type="button">' . Html::encode($section->name) . '</button> ';
    $formFields .= '<div id="screen' . $sectionNumber . '" class="form-section ' . $class . '"><h4>' . Html::encode($section->name) . '</h4>';
    $fields = $section->getFields()->orderBy(['position' => SORT_ASC])->all();
    foreach ($fields as $field):
        $fieldId = $field->id;
        $fieldName = "ApplicationsForm[fields][$fieldId]";
        $fieldLabel = $field->label;
        $fieldValue = isset($formModel->fields[$fieldId]) ? $formModel->fields[$fieldId] : '';

        switch ($field->type) {
            case 'select':
                $optionArr = json_decode($field->options, true);
                $formFields .= $form->field($formModel, "fields[$fieldId]")
                    ->dropDownList($optionArr, ['name' => $fieldName, 'value' => $fieldValue])
                    ->label($fieldLabel);
                break;
            default:
                $formFields .= $form->field($formModel, "fields[$fieldId]")
                    ->textInput(['name' => $fieldName, 'value' => $fieldValue])
                    ->label($fieldLabel);
                break;
        }
    endforeach;

    $nextIndex = $sectionNumber + 1;
    $prevIndex = $sectionNumber - 1;
    $prevBtn = '<button class="btn btn-secondary" onclick="showSection(' . $prevIndex . ')" type="button">Назад</button> ';
    $nextBtn = '<button class="btn btn-secondary" onclick="showSection(' . $nextIndex . ')" type="button">Далее</button> ';

    if ($sectionNumber == 1) {
        $class = '';
        $prevBtn = '';
    } elseif ($sectionNumber == count($sections)) {
        $nextBtn = '';
    }

    $formFields .= '<div class="d-flex justify-content-between">
        <div>' . $prevBtn . $nextBtn . '</div>'.
        Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) .
        '</div>';
    $formFields .= '</div>';
    $sectionNumber = $nextIndex;
}
?>

    <div id="form-navigation" class="mb-4">
        <?= $navigation ?>
    </div>

<?= $formFields ?>

<?php ActiveForm::end(); ?>