<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%fields}}`.
 */
class m241116_113233_create_fields_table extends Migration
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
        $this->createTable('{{%fields}}', [
            'id' => $this->primaryKey(),
            'section_id' => $this->integer(),
            'name' => $this->string()->notNull(),
            'label' => $this->string()->notNull(),
            'type' => $this->string()->defaultValue('text'),
            'options' => $this->text()->defaultValue(null),
            'position' => $this->integer()->defaultValue(0),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-fields-section_id',
            '{{%fields}}',
            'section_id',
            '{{%sections}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-fields-section_id', '{{%fields}}');
        $this->dropTable('{{%fields}}');
    }
}
