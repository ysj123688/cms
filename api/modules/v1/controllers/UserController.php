<?php

	namespace api\modules\v1\controllers;
	use yii\rest\ActiveController;
	use yii\filters\auth\QueryParamAuth;
	use yii\helpers\ArrayHelper;
	use yii\filters\Cors;
	/*
	 *	 用户控制器
	 */
	class UserController extends ActiveController
	{
	    public $modelClass = 'api\modules\v1\models\User';
	    //设计返回json，以及验证access-token
	    public function behaviors()  
	    { 
	    	return ArrayHelper::merge(parent::behaviors(), [
			    'authenticator' => [
			       'class' => QueryParamAuth::className(),
			     ],   
			     [
			       'class' => Cors::className(),
			       'cors' => [
			       
			       		'Access-Control-Request-Method' => ['GET','POST','PUT','DELETE', 'HEAD', 'OPTIONS'],//允许动作的数组
			         ],
			     ],
			     'contentNegotiator' => [
			     	'formats' => ['application/json' => \yii\web\Response::FORMAT_JSON]
			     ]

			    ]); 
	       /* $behaviors = parent::behaviors();  
	        $behaviors['contentNegotiator']['formats'] = ['application/json' => \yii\web\Response::FORMAT_JSON];  
	  		$behaviors['authenticator'] = [  
		        'class' => QueryParamAuth::className(),  
		    ];  

	        return $behaviors; */ 
	    }
	}