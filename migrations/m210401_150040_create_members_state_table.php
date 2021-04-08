<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%members_state}}`.
 */
class m210401_150040_create_members_state_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        sleep(5);
        $this->createTable('{{%members_state}}', [
            'id' => $this->primaryKey(),
            'member_id' => \yii\db\Schema::TYPE_INTEGER,
            'is_hand_raised' => \yii\db\Schema::TYPE_BOOLEAN,
        ]);

        // creates index for column `member_id`
        $this->createIndex(
            'idx-members_state-member_id',
            'members_state',
            'member_id'
        );

        // add foreign key for table `members`
        $this->addForeignKey(
            'fk-members_state-member_id',
            'members_state',
            'member_id',
            'members',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `members`
        $this->dropForeignKey(
            'fk-members_state-member_id',
            'members_state'
        );

        // drops index for column `member_id`
        $this->dropIndex(
            'idx-members_state-member_id',
            'members_state'
        );

        $this->dropTable('{{%members_state}}');
    }
}
