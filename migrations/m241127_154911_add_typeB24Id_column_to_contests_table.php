<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%contests}}`.
 */
class m241127_154911_add_typeB24Id_column_to_contests_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%contests}}', 'typeB24Id', $this->integer()->after('name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%contests}}', 'typeB24Id');
    }
}
