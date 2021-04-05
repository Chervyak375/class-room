<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%members}}`.
 */
class m210401_145558_create_members_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%members}}', [
            'id' => $this->primaryKey(),
            'first_name' => \yii\db\Schema::TYPE_STRING,
            'last_name' => \yii\db\Schema::TYPE_STRING,
            'email' => $this->string(320)->notNull()->unique(),
            'auth_key' => $this->string(32),
            'access_token' => $this->string(32),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%members}}');
    }
}
