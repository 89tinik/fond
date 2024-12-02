<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%companies}}`.
 */
class m241129_114229_create_companies_table extends Migration
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
        $this->createTable('{{%companies}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'b24Id' => $this->integer(),
            'name' => $this->string()->notNull()
        ], $tableOptions);

        $this->addForeignKey(
            'fk-companies-user_id',
            '{{%companies}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-companies-user_id', '{{%companies}}');
        $this->dropTable('{{%companies}}');
    }
}
