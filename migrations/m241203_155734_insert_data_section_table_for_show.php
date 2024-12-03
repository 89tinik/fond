<?php

use yii\db\Migration;

/**
 * Class m241203_155731_insert_data_section_table_for_event
 */
class m241203_155734_insert_data_section_table_for_show extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $contestId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%contests}}')
            ->where(['name' => 'Выставки'])
            ->scalar();

        if ($contestId) {
            $this->batchInsert('{{%sections}}', ['contest_id', 'name', 'position'], [
                [$contestId, 'Экран 1', 1],
                [$contestId, 'Экран 2', 2],
                [$contestId, 'Экран 3', 3],
                [$contestId, 'Экран 4', 4],
                [$contestId, 'Экран 5', 5],
                [$contestId, 'Экран 6', 6],
            ]);
        } else {
            echo "Конкурс 'Выставки' не найден.\n";
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $contestId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%contests}}')
            ->where(['name' => 'Выставки'])
            ->scalar();

        if ($contestId) {
            $this->delete('{{%sections}}', ['contest_id' => $contestId, 'name' => [
                'Экран 1',
                'Экран 2',
                'Экран 3',
                'Экран 4',
                'Экран 5',
                'Экран 6',
            ]]);
        }
    }
}
