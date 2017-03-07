<?php
namespace common\models\forms;

use Yii;
use yii\base\Model;
use common\models\NormalUser;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_normal_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $normal_user = $this->getNormalUser();
            if (!$normal_user || !$normal_user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate() && $this->getUser()->isActive()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
    
    /**
     * Finds admin by email
     *
     * @return Admin|null
     */
    public function getNormalUser()
    {
        if ($this->_normal_user === false) {
            $this->_normal_user = NormalUser::findOne(['email' => $this->email]);
        }
        return $this->_normal_user;
    }

    /**
     * Finds user
     *
     * @return \common\models\User|null
     */
    protected function getUser()
    {
        if ($this->getNormalUser() !== null) {
            return $this->getNormalUser()->user;
        }

        return null;
    }
}
