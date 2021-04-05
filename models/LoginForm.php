<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read UserIdentity|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $email;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // Email are both required
            [['email'], 'required'],
            // email is Email address
            ['email', 'email'],
            // email is validated by validateEmail()
            //['email', 'validateEmail'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validateEmail($this->email)) {
                $this->addError($attribute, 'Incorrect email.');
            }
        }
    }

    /**
     * Logs in a user using the provided email.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if(!$user)
            {
                if($this->signup()) {
                    $user = $this->getUser();
                }
            }
            $success = Yii::$app->user->login($user);
            if($success)
                Yii::$app->session->set('user_id', $user->id);
            return $success;
        }
        return false;
    }

    /**
     * Register in a user using the provided email.
     * @return bool whether the user is registered in successfully
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->email = $this->email;
            return $user->save();
        }
        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return UserIdentity|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $user = UserIdentity::findByEmail($this->email);
            if($user)
                $this->_user = $user;
        }

        return $this->_user;
    }
}
