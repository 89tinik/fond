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
<?=  $form->field($formModel, "sendB24", ['template' => '{input}'])->hiddenInput(['value'=>'0'])?>
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
            case 'file':
                if ($field->multi == 1) {
                    $formFields .= $form->field($formModel, "fields[$fieldId]", [
                        'template' => '{label}<div id="uploaded-fields-' . $fieldId . '-list">' .
                            $this->render('uploadedFiles', ['files' => $fieldValue, 'fieldId' => $fieldId]) .
                            '</div>{input}<div id="applicationsform-fields-' . $fieldId . '-list"></div>{error}',
                    ])->fileInput(['name' => $fieldName, 'class' => 'multifile'])
                        ->label($fieldLabel);

                } else {
                    $formFields .= $form->field($formModel, "fields[$fieldId]",)
                        ->fileInput(['name' => $fieldName, 'value' => $fieldValue])
                        ->label($fieldLabel);
                }
                break;
            default:
                if ($field->multi == 1) {
                    $valueArr = json_decode($fieldValue, true) ?? [''];

                    $first = true;
                    foreach ($valueArr as $value) {
                        if ($first) {
                            $formFields .= '<div class="mb-3">';
                            $formFields .= Html::label($fieldLabel, $fieldId, ['class' => 'form-label']);
                            $formFields .= '<div id="fieldsList_' . $fieldId . '">';
                        }
                        $formFields .= $form->field($formModel, "fields[$fieldId][]", [
                            'template' => '{input}{error}',
                        ])->textInput([
                            'class' => 'form-control mb-2',
                            'placeholder' => $fieldLabel,
                            'value' => $value
                        ])->label(false);

                        $first = false;
                    }
                    $formFields .= '</div>';

                    $formFields .= Html::button('Добавить ещё', [
                        'type' => 'button',
                        'class' => 'btn btn-link',
                        'onclick' => "addField('fieldsList_{$fieldId}', '{$fieldLabel}', '{$fieldId}')"
                    ]);
                    $formFields .= '</div>';

                } else {
                    $formFields .= $form->field($formModel, "fields[$fieldId]")
                        ->textInput(['name' => $fieldName, 'value' => $fieldValue])
                        ->label($fieldLabel);
                }
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
        <div>' . $prevBtn . $nextBtn . '</div>' .
        Html::button('Сохранить', ['class' => 'btn btn-primary', 'onclick' => 'saveDraft(false)']) .
        Html::button('Отправить', ['class' => 'btn btn-primary', 'onclick' => 'saveDraft(true)']) .
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