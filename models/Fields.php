<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fields".
 *
 * @property int $id
 * @property int|null $section_id
 * @property string $name
 * @property string $label
 * @property string|null $type
 * @property string|null $options
 * @property int|null $position
 *
 * @property ApplicationValue[] $applicationValues
 * @property Sections $section
 */
class Fields extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fields';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['section_id', 'position', 'multi'], 'integer'],
            [['name', 'label'], 'required'],
            [['options', 'b24entity'], 'string'],
            [['name', 'label', 'type'], 'string', 'max' => 255],
            [['section_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sections::class, 'targetAttribute' => ['section_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'section_id' => 'Section ID',
            'name' => 'Name',
            'label' => 'Label',
            'type' => 'Type',
            'multi' => 'Multi',
            'options' => 'Options',
            'b24entity' => 'B24entity',
            'position' => 'Position',
        ];
    }

    /**
     * Gets query for [[ApplicationValues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationValues()
    {
        return $this->hasMany(ApplicationValue::class, ['field_id' => 'id']);
    }

    /**
     * Gets query for [[Section]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(Sections::class, ['id' => 'section_id']);
    }
}
