<?php

namespace app\models;

use Yii;

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
}
