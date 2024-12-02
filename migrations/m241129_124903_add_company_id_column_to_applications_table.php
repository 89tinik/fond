<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%applications}}`.
 */
class m241129_124903_add_company_id_column_to_applications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%applications}}', 'company_id', $this->integer()->after('user_id'));
        $this->addForeignKey(
            'fk-applications-company_id',
            '{{%applications}}',
            'company_id',
            '{{%companies}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-applications-company_id', '{{%applications}}');
        $this->dropColumn('{{%applications}}', 'company_id');
    }
}
