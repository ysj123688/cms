<?php

namespace common\models\forms;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use common\models\User;
use common\models\NormalUser;

/**
 * Signup form for normal users
 * 
 * @author Rostislav Pleshivtsev Oparina
 * @link bytehunter.net
 *
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;
    
    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::rules()
     */
    public function rules()
    {
        return [
            [
                ['username','email','password','password_repeat'], 'required',
                'message' => Yii::t('common/forms','Required field')
            ],
            ['username', 'string', 'min' => 3, 'max' => 32],
            [
                'username', 'match',
                'pattern' => "/^[0-9a-z]+$/i",
                'message' => Yii::t('common/forms','Use only letters and numbers')
            ],
            ['email', 'email'],
            // Uniqueness test
            [
                'username', 'unique',
                'targetClass' => '\common\models\NormalUser',
                'targetAttribute' => 'username',
                'message' => Yii::t('common/forms','This username is already in use')
            ],
            [
                'username', 'unique',
                'targetClass' => '\common\models\Admin',
                'targetAttribute' => 'username',
                'message' => Yii::t('common/forms','This username is already in use')
            ],
            [
                'email', 'unique',
                'targetClass' => '\common\models\NormalUser',
                'targetAttribute' => 'email',
                'message' => Yii::t('common/forms','This email is already in use')
            ],
            [
                'email', 'unique',
                'targetClass' => '\common\models\Admin',
                'targetAttribute' => 'email',
                'message' => Yii::t('common/forms','This email is already in use')
            ],
            // Password validation
            [
                'password_repeat', 'compare',
                'compareAttribute' => 'password',
                'message' => Yii::t('common/forms','Passwords don\'t match')
            ],
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::attributeLabels()
     */
    public function attributeLabels()
    {
        return [
            'username'          => Yii::t('common/forms','Username'),
            'email'             => Yii::t('common/forms','Email'),
            'password'          => Yii::t('common/forms','Password'),
            'password_repeat'   => Yii::t('common/forms','Repeat password'),
        ];
    }
    
    /**
     * Signup process
     * @return boolean
     */
    public function signup()
    {
        $normal_user = $this->createNormalUser();
        if ($normal_user !== false) {
            $this->sendMail($normal_user);
            return $normal_user->user;
        }
        
        return null;
    }
    
    /**
     * Create client model. If creation was impossible returns false
     * @return \common\models\NormalUser|boolean
     */
    protected function createNormalUser()
    {
        $normal_user = new NormalUser([
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password
        ]);
        
        if ($normal_user->save()) {
            return $normal_user;
        } else {
            return false;
        }
    }
    
    /**
     * Sends confirmation email
     * @param \common\models\NormalUser $normal_user
     * @return boolean
     */
    public function sendMail($normal_user)
    {
        //Compose and Send confirmation mail
        $confirm_link = Url::to([
            'site/confirm-account',
            'token' => $normal_user->user->account_confirm_token
        ],true);
        
        $subject = "Welcome to " . Yii::$app->params['title'];
        $body = '<h1>Welcome to '.Yii::$app->params['title'].'</h1>';
        $body.= '<p>Thanks for registering in <strong>' . Yii::$app->params['title'];
        $body.= '</strong>.<br>Use the link below to confirm your account:</p>';
        $body.= '<a href="'.$confirm_link.'"> Confirm my Account</a>';
        
        Yii::$app->mailer->compose()
            ->setTo($normal_user->email)
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['title']])
            ->setSubject($subject)
            ->setHtmlBody($body)
            ->send();
    }
}