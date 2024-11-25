<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class BitrixForm extends Model
{
    public $title;
    public $category_id;
    public $uf_crm_deal_1690807644497;
    public $uf_crm_deal_1691729934958; // Поле для файлов

    public function rules()
    {
        return [
            [['title', 'category_id', 'uf_crm_deal_1690807644497'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['category_id'], 'integer'],
            [['uf_crm_deal_1690807644497'], 'string', 'max' => 255],
            [['uf_crm_deal_1691729934958'], 'each', 'rule' => ['file', 'extensions' => 'jpg, png, pdf, docx, txt', 'maxSize' => 1024 * 1024 * 5]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Название',
            'category_id' => 'Категория',
            'uf_crm_deal_1690807644497' => 'Кастомное поле',
            'uf_crm_deal_1691729934958' => 'Файлы',
        ];
    }
}
