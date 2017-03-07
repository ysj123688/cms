<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "admin".
 *
 * @property string $id
 * @property string $user_id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $fullname
 * @property string $phone
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \common\models\User $user
 */
class Admin extends \yii\db\ActiveRecord
{
    use \common\traits\AuthenticationModelTrait;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }
    
    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'value' => new \yii\db\Expression('now()'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'username', 'email', 'password_hash'], 'required'],
            [['user_id'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['username', 'email', 'password_hash'], 'string', 'max' => 255],
            // Uniqueness
            [['username', 'email'], 'unique'],
            [
                'username', 'unique',
                'skipOnError' => false,
                'targetClass' => NormalUser::className(),
                'targetAttribute' => ['username' => 'username']
            ],
            [
                'email', 'unique',
                'skipOnError' => false,
                'targetClass' => NormalUser::className(),
                'targetAttribute' => ['email' => 'email']
            ],
            [
                'user_id', 'exist',
                'skipOnError' => false,
                'targetClass' => User::className(),
                'targetAttribute' => ['user_id' => 'id']
            ],
            // Prevent password field from validating
            [['password'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('common/models', 'ID'),
            'user_id'       => Yii::t('common/models', 'User ID'),
            'username'      => Yii::t('common/models', 'Username'),
            'email'         => Yii::t('common/models', 'Email'),
            'password_hash' => Yii::t('common/models', 'Password Hash'),
            'fullname'      => Yii::t('common/models', 'Fullname'),
            'phone'         => Yii::t('common/models', 'Phone'),
            'created_at'    => Yii::t('common/models', 'Created At'),
            'updated_at'    => Yii::t('common/models', 'Updated At'),
        ];
    }
    
    /**
     * Automátically generates new `User` model to link to.
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->setPassword($this->password);
                $user = new User(['type' => User::TYPE_ADMIN]);
                $user->generateAuthKey();
                $user->generateAccountConfirmToken();
                $user->save();
                $this->user_id = $user->id;
            }
            return true;
        } else {
            return false;
        }
    }
}
