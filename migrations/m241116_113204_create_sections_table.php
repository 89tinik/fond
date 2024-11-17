<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sections}}`.
 */
class m241116_113204_create_sections_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%sections}}', [
            'id' => $this->primaryKey(),
            'contest_id' => $this->integer(),
            'name' => $this->string()->notNull(),
            'position' => $this->integer()->defaultValue(0),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-sections-contest_id',
            '{{%sections}}',
            'contest_id',
            '{{%contests}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-sections-contest_id', '{{%sections}}');
        $this->dropTable('{{%sections}}');
    }
}
