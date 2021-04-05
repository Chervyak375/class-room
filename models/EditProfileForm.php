<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class EditProfileForm extends Model
{
    public $first_name;
    public $last_name;
    public $email;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email'], 'required'],
            [['first_name', 'last_name'], 'string', 'max' => 60],
            ['email', 'email'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function edit()
    {
        if ($this->validate()) {
            /**
             * @var UserIdentity $user
             */
            $user = Yii::$app->user->identity;
            $user->setAttributes($this->getAttributes());
            $user->update();

            return true;
        }
        return false;
    }
}
