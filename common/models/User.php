<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\db\Expression;

/**
 * User model
 *
 * @property string $id
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $account_confirm_token
 * @property string $type
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $last_activity
 * 
 * @property Admin $admin
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $itemNames
 * @property NormalUser $normalUser
 */
class User extends ActiveRecord implements IdentityInterface
{
    use \common\traits\DeleteExceptionTrait;
    
    const TYPE_ADMIN    = 'admin';
    const TYPE_NORMAL   = 'normal';
    const STATUS_DELETED    = 'deleted';
    const STATUS_SUSPENDED  = 'suspended';
    const STATUS_ACTIVE     = 'active';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('now()'),
            ]
        ];
    }
    
    /**
     * After saving refresh model to get rid of 'Expression Now()` y datetime fields
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::afterSave()
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->refresh();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_key'], 'required'],
            [['type', 'status'], 'string'],
            [['created', 'updated', 'last_activity'], 'safe'],
            [['auth_key'], 'string', 'max' => 32],
            [['password_reset_token', 'account_confirm_token'], 'string', 'max' => 255],
            [['password_reset_token'], 'unique'],
            [['account_confirm_token'], 'unique'],
            ['type', 'default', 'value' => self::TYPE_NORMAL],
            ['type', 'in', 'range' => [
                self::TYPE_ADMIN, self::TYPE_NORMAL
            ]],
            ['status', 'default', 'value' => self::STATUS_SUSPENDED],
            ['status', 'in', 'range' => [
                self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_SUSPENDED
            ]],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                    => Yii::t('common/models', 'ID'),
            'auth_key'              => Yii::t('common/models', 'Auth Key'),
            'password_reset_token'  => Yii::t('common/models', 'Password Reset Token'),
            'account_confirm_token' => Yii::t('common/models', 'Account Confirm Token'),
            'type'                  => Yii::t('common/models', 'Type'),
            'status'                => Yii::t('common/models', 'Status'),
            'created_at'            => Yii::t('common/models', 'Created At'),
            'updated_at'            => Yii::t('common/models', 'Updated At'),
            'last_activity'         => Yii::t('common/models', 'Last Activity'),
        ];
    }
    
    /**
     * @return string[]
     */
    public function typeLabels()
    {
        return [
            self::TYPE_ADMIN    => Yii::t('common/models','Admin'),
            self::TYPE_NORMAL   => Yii::t('common/models','Normal User'),
        ];
    }
    
    /**
     * @return string[]
     */
    public function statusLabels()
    {
        return [
            self::STATUS_ACTIVE     => Yii::t('common/models','Active'),
            self::STATUS_DELETED    => Yii::t('common/models','Deleted'),
            self::STATUS_SUSPENDED  => Yii::t('common/models','Suspended'),
        ];
    }
    
    /* ---------------------------------------------------------------------------------------------
     * Relations
     * ------------------------------------------------------------------------------------------ */
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemNames()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNormalUser()
    {
        return $this->hasOne(NormalUser::className(), ['user_id' => 'id']);
    }
    
    /* ---------------------------------------------------------------------------------------------
     * Identity methods
     * ------------------------------------------------------------------------------------------ */

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne([
            'id' => $id,
            'status' => static::STATUS_ACTIVE
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not supported.');
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => static::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }
    
    /**
     * Finds user by account confirmation token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByAccountConfirmToken($token)
    {
        return static::findOne([
            'account_confirm_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    /**
     * Generates new account confirmation token
     */
    public function generateAccountConfirmToken()
    {
        $this->account_confirm_token = Yii::$app->security->generateRandomString();
    }
    
    /**
     * Removes account confirmation token
     */
    public function removeAccountConfirmToken()
    {
        $this->account_confirm_token = null;
    }
    
    /* ------------------------------------------------------------------------
     * Utilities
     * ------------------------------------------------------------------------ */
    
    /**
     * Activate user
     * @return number|false
     */
    public function activate()
    {
        $this->status = static::STATUS_ACTIVE;
        return $this->update();
    }
    
    /**
     * Suspends this user
     * @return number|false
     */
    public function suspend()
    {
        $this->status = static::STATUS_SUSPENDED;
        return $this->update();
    }
    
    /**
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->type === self::TYPE_ADMIN;
    }
    
    /**
     * @return boolean
     */
    public function isNormal()
    {
        return $this->type === self::TYPE_NORMAL;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }
    
    /**
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->status === self::STATUS_DELETED;
    }
    
    /**
     * @return boolean
     */
    public function isSuspended()
    {
        return $this->status === self::STATUS_SUSPENDED;
    }
    
    /**
     * Overrides the default getter to call the username of specific child model
     * API Users are not listed here
     * @return mixed string|null The username
     */
    public function getUsername()
    {
        if ( $this->isAdmin() && $this->admin !== null ) {
            return $this->admin->username;
        }
        if ( $this->isNormal() && $this->normalUser !== null ) {
            return $this->normalUser->username;
        }
    
        return null;
    }

    /**
     * @return string Type of user in a more friendly format
     */
    public function getTypeLabel()
    {
        return $this->typeLabels()[$this->type];
    }
    
    /**
     * @return string Status label
     */
    public function getStatusLabel()
    {
        return $this->statusLabels()[$this->status];
    }
}
