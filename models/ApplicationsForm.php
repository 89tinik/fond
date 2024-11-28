<?php

namespace app\models;

use yii\base\Model;

class ApplicationsForm extends Model
{
    public $fields = [];
    public $contestId;
    public $new;
    public $sendB24;

    /**
     * @param int $contestId
     * @param bool $new
     * @param array $config
     */
    public function __construct($contestId, $new = true, $config = [])
    {
        $this->contestId = $contestId;
        $this->new = $new;
        parent::__construct($config);
    }

    /**
     * @return void
     */
    public function init()
    {
        if ($this->contestId === null) {
            parent::init();
            return;
        }

        $sectionIds = Sections::find()
            ->select('id')
            ->where(['contest_id' => $this->contestId])
            ->column();

        $fields = Fields::find()
            ->where(['section_id' => $sectionIds])
            ->orderBy(['position' => SORT_ASC])
            ->all();

        foreach ($fields as $field) {
            $this->fields[$field->id] = null;
        }

        parent::init();
    }

    /**
     * @return array[]
     */
    public function rules()
    {
        return [
            [array_keys($this->fields), 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [];
        $fields = Fields::find()
            ->where(['section_id' => Sections::find()->select('id')->where(['contest_id' => $this->contestId])])
            ->all();
        foreach ($fields as $field) {
            $labels[$field->id] = $field->label;
        }
        return $labels;
    }
}