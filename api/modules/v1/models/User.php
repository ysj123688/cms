<?php
	/*
	 *   用户模型
	 */
	namespace api\modules\v1\models;
	use Yii;
    use yii\db\ActiveRecord;
 	use yii\web\IdentityInterface;
	class User extends ActiveRecord implements IdentityInterface
	{
		const STATUS_DELETED = 0;//用户被禁用
		const STATUS_ACTIVE = 1;//用户启用
		//过滤到敏感数据
		public function fields()
		{
		    $fields = parent::fields();

		    // remove fields that contain sensitive information
		    unset($fields['password'], $fields['token']);

		    return $fields;
		}

		public function scenarios()
	    {
	        return [
	            'default'=>['username','password','last_login_date','active','rank','token']
	        ];
	    }
		//启用token认证，有权限的用户才可进行API的使用
	    public static function findIdentityByAccessToken($token, $type = null)
	    {
	        return static::findOne(['token' => $token]);
	    }
	    public static function accessAble($token)
	    {
	    	return self::findIdentityByAccessToken($token);
	    }
	    public static function findIdentity($id)
	    {
	        return static::findOne(['uid'=>$id, 'active'=>self::STATUS_ACTIVE]);
	    }
	    public function getId()
	    {
	        return $this->getPrimaryKey();
	    }
	    public function getAuthKey()
	    {
	        //return $this->authKey;
	    }
	    public function validateAuthKey($authKey)
	    {
	        //return $this->authKey === $authKey;
	    }
	   
	}

