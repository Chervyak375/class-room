<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "members_state".
 *
 * @property int $id
 * @property int|null $member_id
 * @property bool|null $is_hand_raised
 *
 * @property User $member
 */
class UserState extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'members_state';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id'], 'integer'],
            [['is_hand_raised'], 'boolean'],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['member_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'is_hand_raised' => 'Is Hand Raised',
        ];
    }

    /**
     * Gets query for [[Member]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'member_id']);
    }
}
