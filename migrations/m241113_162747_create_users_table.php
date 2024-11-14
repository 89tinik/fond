<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m241113_162747_create_users_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'firstname' => $this->string(255)->notNull(),
            'surname' => $this->string(255)->notNull(),
            'lastname' => $this->string(255)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'phone' => $this->string()->notNull()->unique(),

            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%users}}');
    }
}
