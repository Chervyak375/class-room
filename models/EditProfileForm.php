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
            ['email', 'validateEmail'],
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
     * Validates the email.
     * This method serves as the inline validation for email.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $userNow = User::find()->where(['id' => Yii::$app->session->get('user_id')])->one();
            $userCount = 0;
            if($userNow->email != $this->email)
                $userCount = User::find()->where(['email' => $this->email])->count();

            if ($userCount) {
                $this->addError($attribute, 'Email должен быть уникальным!');
            }
        }
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
