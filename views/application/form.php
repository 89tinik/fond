<?php


use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\ApplicationsForm $formModel */
/** @var app\models\Sections[] $sections */
/** @var string $type */
/** @var array $companies */
/** @var bool $noUpdate */

$navigation = '';
$formFields = '';
?>
<?php $form = ActiveForm::begin(['id' => 'application-form']); ?>
<?= $form->field($formModel, "sendB24", ['template' => '{input}'])->hiddenInput(['value' => '0']) ?>

<?php
if ($companies) {
    $attrArray['prompt'] = 'Выберите компанию';
    if ($noUpdate) {
        $attrArray['disabled'] = 'disabled';
    }
    echo $form->field($formModel, 'companyId')->dropDownList($companies, $attrArray);
} ?>
<?php
$sectionNumber = 1;
$class = 'active';
foreach ($sections as $section) {
    $navigation .= '<button class="btn btn-secondary" onclick="showSection(' . $sectionNumber . ')" type="button">' . Html::encode($section->name) . '</button> ';
    $formFields .= '<div id="screen' . $sectionNumber . '" class="form-section ' . $class . '" section-number="' . ($sectionNumber - 1) . '"><h4>' . Html::encode($section->name) . '</h4>';
    $fields = $section->getFields()->orderBy(['position' => SORT_ASC])->all();
    foreach ($fields as $field):
        $fieldId = $field->id;
        $fieldName = "ApplicationsForm[fields][$fieldId]";
        $fieldLabel = $field->label;
        $fieldValue = isset($formModel->fields[$fieldId]) ? $formModel->fields[$fieldId] : '';

        $inputClass = $field->required == 1 ? ' required-b24' : '';
        if ($noUpdate) {
            $attrArray['disabled'] = 'disabled';
        } else {
            $attrArray = [];
        }
        switch ($field->type) {
            case 'select':
                $attrArray['name'] = $fieldName;
                $attrArray['value'] = $fieldValue;
                $optionArr = json_decode($field->options, true);
                $formFields .= $form->field($formModel, "fields[$fieldId]")
                    ->dropDownList($optionArr, $attrArray)
                    ->label($fieldLabel);
                break;
            case 'file':
                $attrArray['name'] = $fieldName;
                if ($field->multi == 1) {
                    $inputClass .= ' multifile';
                    $attrArray['class'] = $inputClass;
                    $formFields .= $form->field($formModel, "fields[$fieldId]", [
                        'template' => '{label}<div id="uploaded-fields-' . $fieldId . '-list" class="wrapper-uploaded">' .
                            $this->render('uploadedFiles', ['files' => $fieldValue, 'fieldId' => $fieldId, 'noUpdate' => $noUpdate]) .
                            '</div>{input}<div id="applicationsform-fields-' . $fieldId . '-list" class="wrapper-no-loaded"></div>{error}',
                    ])->fileInput($attrArray)
                        ->label($fieldLabel);

                } else {
                    $attrArray['value'] = $fieldValue;
                    $attrArray['class'] = $inputClass;
                    $formFields .= $form->field($formModel, "fields[$fieldId]",)
                        ->fileInput($attrArray)
                        ->label($fieldLabel);
                }
                break;
            default:
                $inputClass .= ' form-control mb-2';
                if ($field->multi == 1) {
                    $attrArray ['placeholder'] = $fieldLabel;
                    $valueArr = json_decode($fieldValue, true) ?? [''];
                    if ($field->b24entity == 'company') {
                        if ($formModel->companyId) {
                            $attrArray['readonly'] = 'readonly';
                        }
                        $inputClass .= ' company-field';
                    }
                    $attrArray['class'] = $inputClass;
                    $first = true;
                    foreach ($valueArr as $value) {
                        $attrArray['value'] = $value;
                        if ($first) {
                            $formFields .= '<div class="mb-3">';
                            $formFields .= Html::label($fieldLabel, $fieldId, ['class' => 'form-label']);
                            $formFields .= '<div id="fieldsList_' . $fieldId . '" class="wrapper-multi">';
                        }
                        $formFields .= $form->field($formModel, "fields[$fieldId][]", [
                            'template' => '{input}{error}',
                        ])->textInput($attrArray)->label(false);

                        $first = false;
                    }
                    $formFields .= '</div>';
                    if (!$noUpdate) {
                        $formFields .= Html::button('Добавить ещё', [
                            'type' => 'button',
                            'class' => 'btn btn-link',
                            'onclick' => "addField('fieldsList_{$fieldId}', '{$fieldLabel}', '{$fieldId}')"
                        ]);
                    }
                    $formFields .= '</div>';

                } else {
                    $attrArray['name'] = $fieldName;
                    if ($field->b24entity == 'company') {
                        if ($formModel->companyId) {
                            $attrArray['readonly'] = 'readonly';
                        }
                        $inputClass .= ' company-field';
                    }
                    $attrArray['class'] = $inputClass;
                    if ($field->b24entity == 'contact') {
                        switch ($field->name) {
                            case 'LAST_NAME':
                                $fieldValue = Yii::$app->user->identity->lastname;
                                $attrArray['readonly'] = 'readonly';
                                break;
                            case 'NAME':
                                $fieldValue = Yii::$app->user->identity->firstname;
                                $attrArray['readonly'] = 'readonly';
                                break;
                            case 'SECOND_NAME':
                                $fieldValue = Yii::$app->user->identity->surname;
                                $attrArray['readonly'] = 'readonly';
                                break;
                            case 'POST':
                                if (!empty(Yii::$app->user->identity->post)) {
                                    $fieldValue = Yii::$app->user->identity->post;
                                    $attrArray['readonly'] = 'readonly';
                                }
                                break;
                            case 'PHONE':
                                $fieldValue = Yii::$app->user->identity->phone;
                                $attrArray['readonly'] = 'readonly';
                                break;
                            case 'EMAIL':
                                $fieldValue = Yii::$app->user->identity->email;
                                $attrArray['readonly'] = 'readonly';
                                break;
                        }
                    }
                    $attrArray['value'] = $fieldValue;
                    $formFields .= $form->field($formModel, "fields[$fieldId]")
                        ->input($field->type, $attrArray)
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
        <div>' . $prevBtn . $nextBtn . '</div>';
    if (!$noUpdate) {
        $formFields .= Html::button('Сохранить', ['class' => 'btn btn-primary', 'onclick' => 'saveDraft(false)']) .
            Html::button('Отправить', ['class' => 'btn btn-primary', 'onclick' => 'saveDraft(true)']);
    }
    $formFields .= '</div>';
    $formFields .= '</div>';
    $sectionNumber = $nextIndex;
}
?>

    <div id="form-navigation" class="mb-4">
        <?= $navigation ?>
    </div>

<?= $formFields ?>

<?php ActiveForm::end(); ?>