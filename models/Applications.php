<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "applications".
 *
 * @property int $id
 * @property int|null $contest_id
 * @property int|null $user_id
 * @property string|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ApplicationValue[] $applicationValues
 * @property Contests $contest
 */
class Applications extends \yii\db\ActiveRecord
{

    /**
     * @param $contest
     * @param $config
     */
    public function __construct($contest = null, $config = [])
    {
        parent::__construct($config);

        if ($contest !== null) {
            $this->contest_id = $contest;
        }
        $this->user_id = Yii::$app->user->id;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contest_id', 'user_id', 'company_id'], 'integer'],
            [['status'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
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
            'user_id' => 'User ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[ApplicationValues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationValues()
    {
        return $this->hasMany(ApplicationValue::class, ['application_id' => 'id']);
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
     * Gets query for [[Section]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Companies::class, ['id' => 'company_id']);
    }
}
