<?php

	namespace api\modules\v1\controllers;
	use yii\web\Controller;
	use yii\filters\auth\QueryParamAuth;
	use api\modules\v1\models\User;
	use Yii;
	/*
	 *	 登录控制器
	 */
	class LoginController extends Controller
	{
		public $layout = false;
		public $enableCsrfValidation = false;//非常重要
	    public function actionIndex(){

	    	$request = Yii::$app->request;
	    	$username = $request->post('username');
	    	$password = $request->post('password');

	    	$res = User::find()->where(['username' => $username, 'password'=>$password])->one();
	    	
	    	if(empty($res)){
	    		$response = array(
	    			 "name" => "Unauthorized",
					  "message" => "Your request was made with invalid credentials.",
					  "code" => 0,
					  "status" => 401,
					  "type" => "yii\\web\\UnauthorizedHttpException"
	    		);
	    		echo json_encode($response);
	    	}else{
	    		$response = array(
	    			  "name" => "success",
					  "message" => "login success",
					  "code" => 1,
					  "status" => 200,
					  "token" => $res['token']
	    		);
	    		echo json_encode($response);
	    	}
	    }
	}