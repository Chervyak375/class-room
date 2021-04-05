<?php

namespace app\models;

use yii\redis\ActiveQuery;

/**
 * Class Online
 * @property integer $user_id
 * @property bool $online
 * @package app\models
 */
class Online extends \yii\redis\ActiveRecord
{
    /**
     * @return array the list of attributes for this record
     */
    public function attributes()
    {
        return ['user_id', 'online'];
    }

    public static function primaryKey()
    {
        return ['user_id'];
    }

    public static function find()
    {
        return new CustomerQuery(get_called_class());
    }
}

class CustomerQuery extends \yii\redis\ActiveQuery
{
    public function online()
    {
        return $this->andWhere(['online' => true]);
    }

    public function offline()
    {
        return $this->andWhere(['online' => false]);
    }

    public function byUserId($userId)
    {
        return $this->andWhere(['user_id' => $userId])->one();
    }
}