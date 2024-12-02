<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}}`.
 */
class m241129_103514_add_b24Id_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'post', $this->integer()->after('phone'));
        $this->addColumn('{{%users}}', 'b24Id', $this->string()->after('post'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'post');
        $this->dropColumn('{{%users}}', 'b24Id');
    }
}
