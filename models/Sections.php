<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sections".
 *
 * @property int $id
 * @property int|null $contest_id
 * @property string $name
 * @property int|null $position
 *
 * @property Contests $contest
 * @property Fields[] $fields
 */
class Sections extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sections';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contest_id', 'position'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['contest_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contests::class, 'targetAttribute' => ['contest_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contest_id' => 'Contest ID',
            'name' => 'Name',
            'position' => 'Position',
        ];
    }

    /**
     * Gets query for [[Contest]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContest()
    {
        return $this->hasOne(Contests::class, ['id' => 'contest_id']);
    }

    /**
     * Gets query for [[Fields]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFields()
    {
        return $this->hasMany(Fields::class, ['section_id' => 'id']);
    }
}
