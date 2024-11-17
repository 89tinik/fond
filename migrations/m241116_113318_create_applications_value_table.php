<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%applications_value}}`.
 */
class m241116_113318_create_applications_value_table extends Migration
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
        $this->createTable('{{%application_value}}', [
            'id' => $this->primaryKey(),
            'application_id' => $this->integer(),
            'field_id' => $this->integer(),
            'value' => $this->text(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-application_value-application_id',
            '{{%application_value}}',
            'application_id',
            '{{%applications}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-application_value-field_id',
            '{{%application_value}}',
            'field_id',
            '{{%fields}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-application_value-application_id', '{{%application_value}}');
        $this->dropForeignKey('fk-application_value-field_id', '{{%application_value}}');
        $this->dropTable('{{%application_value}}');
    }
}
