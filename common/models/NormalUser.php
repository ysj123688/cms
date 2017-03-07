<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "normal_user".
 *
 * @property string $id
 * @property string $user_id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $fullname
 * @property string $phone
 * @property string $gender
 * @property string $birth_date
 * @property string $address
 * @property string $zip_code
 * @property string $locality
 * @property string $country
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class NormalUser extends \yii\db\ActiveRecord
{
    use \common\traits\AuthenticationModelTrait;
    
    const GENDER_MALE = 'm';
    const GENDER_FEMALE = 'f';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'normal_user';
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
            [['gender'], 'string'],
            [['birth_date', 'created_at', 'updated_at'], 'safe'],
            [['username', 'email', 'password_hash', 'fullname', 'address', 'locality'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 32],
            [['zip_code'], 'string', 'max' => 10],
            [['country'], 'string', 'max' => 5],
            // Uniqueness
            [['username', 'email'], 'unique'],
            [
                'username', 'unique',
                'skipOnError' => false,
                'targetClass' => Admin::className(),
                'targetAttribute' => ['username' => 'username']
            ],
            [
                'email', 'unique',
                'skipOnError' => false,
                'targetClass' => Admin::className(),
                'targetAttribute' => ['email' => 'email']
            ],
            [
                ['user_id'], 'exist',
                'skipOnError' => false,
                'targetClass' => User::className(),
                'targetAttribute' => ['user_id' => 'id']
            ],
            // Prevent password field from validating
            [['password'], 'safe'],
        ];
    }
    
	/**
     * @return string[]
     */
    public function genderLabels()
	{
        return [
            self::GENDER_MALE => Yii::t('common/models', 'male'),
            self::GENDER_FEMALE => Yii::t('common/models', 'female'),
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
            'gender'        => Yii::t('common/models', 'Gender'),
            'birth_date'    => Yii::t('common/models', 'Birth Date'),
            'address'       => Yii::t('common/models', 'Address'),
            'zip_code'      => Yii::t('common/models', 'Zip Code'),
            'locality'      => Yii::t('common/models', 'Locality'),
            'country'       => Yii::t('common/models', 'Country'),
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
                $user = new User();
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
    
    /* ------------------------------------------------------------------------
     * Utilities
     * ------------------------------------------------------------------------ */
    
    /**
     * @return string
     */
    public function getGenderLabel()
    {
        if ($this->gender === null || empty($this->gender)) {
            return null;
        }
        return $this->genderLabels()[$this->gender];
    }
    
    /**
     * @return boolean
     */
    public function isMale()
    {
        return $this->gender === self::GENDER_MALE;
    }
    /**
     * @return boolean
     */
    public function isFemale()
    {
        return $this->gender === self::GENDER_FEMALE;
    }
}
