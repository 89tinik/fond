<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%fields}}`.
 */
class m241122_122733_add_multi_column_to_fields_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%fields}}', 'multi', $this->integer()->after('type'));
        $this->addColumn('{{%fields}}', 'b24entity', $this->string()->defaultValue('deal')->after('options'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%fields}}', 'multi');
        $this->dropColumn('{{%fields}}', 'b24entity');
    }
}
