<?php

use yii\db\Migration;

/**
 * Class m241116_125254_insert_data_contests_table
 */
class m241116_125254_insert_data_contests_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('{{%contests}}', ['name'], [
            ['Онлайн-проекты'],
            ['Мероприятия'],
            ['Книги'],
            ['Выставки'],
            ['Видеоконтент'],
            ['Археология'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%contests}}', ['name' => [
            'Онлайн-проекты',
            'Мероприятия',
            'Книги',
            'Выставки',
            'Видеоконтент',
            'Археология',
        ]]);
    }
}
