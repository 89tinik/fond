<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "application_value".
 *
 * @property int $id
 * @property int|null $application_id
 * @property int|null $field_id
 * @property string|null $value
 *
 * @property Applications $application
 * @property Fields $field
 */
class ApplicationValue extends \yii\db\ActiveRecord
{
    const UPLOAD_FILES_DIR = 'files/';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['application_id', 'field_id'], 'integer'],
            [['value'], 'string'],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Applications::class, 'targetAttribute' => ['application_id' => 'id']],
            [['field_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fields::class, 'targetAttribute' => ['field_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'application_id' => 'Application ID',
            'field_id' => 'Field ID',
            'value' => 'Value',
        ];
    }

    /**
     * Gets query for [[Application]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplication()
    {
        return $this->hasOne(Applications::class, ['id' => 'application_id']);
    }

    /**
     * Gets query for [[Field]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(Fields::class, ['id' => 'field_id']);
    }

    /**
     * @param UploadedFile[] $uploadedFiles
     * @param array $filesNamesArr
     * @param int $app
     * @return array
     * @throws \yii\db\Exception
     */
    public static function uploadFiles(array $uploadedFiles, array $filesNamesArr, int $app)
    {
        $uploadDir = Yii::getAlias(self::UPLOAD_FILES_DIR . $app);
        $savedFiles = [];
        $idx = 0;
        foreach ($uploadedFiles as $file) {
            preg_match('/\[(\d+)\]/', $filesNamesArr[$idx]['name'], $matches);
            $fullUploadDir = $uploadDir . '/' . $matches[1];
            if (!is_dir($fullUploadDir)) {
                if (!mkdir($fullUploadDir, 0777, true) && !is_dir($uploadDir)) {
                    return ['error' => 'Не удалось создать директорию для файлов'];
                }
            }
            $filePath = $fullUploadDir . '/' . $file->baseName . '.' . $file->extension;
            if (file_exists($filePath)) {
                $filePath = $fullUploadDir . '/' . $file->baseName . '(' . time() . ').' . $file->extension;
            }
            if ($file->saveAs($filePath)) {
                $currentAppVal = self::findOne(['application_id' => $app, 'field_id' => $matches[1]]);
                $i = 1;
                if (!$currentAppVal) {
                    $currentAppVal = new self();
                    $currentAppVal->application_id = $app;
                    $currentAppVal->field_id = $matches[1];
                    $currentAppVal->value = json_encode(['idx' . $i => $filePath]);
                } else {
                    if ($oldValueArr = json_decode($currentAppVal->value, true)) {
                        $i = count($oldValueArr) + 1;
                    } else {
                        $oldValueArr = [];
                    }
                    $currentAppVal->value = json_encode(array_merge($oldValueArr, ['idx' . $i => $filePath]));
                }

                $currentAppVal->save();
                $savedFiles[] = [
                    'idx' => 'idx' . $i,
                    'name' => basename($filePath),
                    'fullUploadDir' => $filePath,
                    'fieldId' => $matches[1]
                ];
            } else {
                return ['error' => 'Ошибка сохранения файла: ' . $file->name];
            }
            $idx++;
        }

        return [
            'success' => true,
            'files' => $savedFiles,
            'message' => 'Файлы успешно сохранены',
        ];
    }

    /**
     * @param string $idx
     * @param int $app
     * @param int $field
     * @return string[]
     * @throws \yii\db\Exception
     */
    public static function deleteFile($idx, $app, $field)
    {
        if ($fieldVal = self::findOne(['application_id' => $app, 'field_id' => $field])) {
            $value = json_decode($fieldVal->value, true);
            unlink($value[$idx]);
            $value[$idx] = '';
            $fieldVal->value = json_encode($value);
            if ($fieldVal->save()) {
                return ['Файл успешно удалён'];
            }
        }
        return ['Ошибка удаления файла'];
    }

    /**
     * @param array $fieldsArr
     * @param int $app
     * @param array|false $existingValues
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function loadFields($fieldsArr, $app, $existingValues = false)
    {
        $fieldsFileArr = Fields::find()->select('id')->where(['type' => 'file'])->column();

        foreach ($fieldsArr as $fieldId => $value) {
            if ($existingValues && in_array($fieldId, $fieldsFileArr)) {
                continue;
            }

            if ($existingValues && isset($existingValues[$fieldId])) {
                $applicationValue = $existingValues[$fieldId];
            } elseif ($value !== '') {
                $applicationValue = new ApplicationValue();
            }

            if (isset($applicationValue)) {
                $applicationValue->field_id = $fieldId;
                $applicationValue->application_id = $app;
                if (is_array($value)) {
                    $applicationValue->value = json_encode($value);
                } else {
                    $applicationValue->value = $value;
                }
                if (!$applicationValue->save()) {
                    return false;
                }
                unset($applicationValue);
            }
        }
        return true;
    }

}
