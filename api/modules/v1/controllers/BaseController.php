<?php
	namespace api\modules\v1\controllers;

	use api\modules\v1\models\User;
	use yii\rest\ActiveController;
	use Yii;
	use yii\web\Response;
	//use yii\filters\auth\QueryParamAuth;
	class BaseController extends ActiveController
	{
	    //public $modelClass = 'api\modules\v1\models\Category';
	    
	    //设计返回json
	    public function behaviors()  
	    {  
	    	
	        $behaviors = parent::behaviors();  
	        $behaviors['contentNegotiator']['formats'] = ['application/json' => Response::FORMAT_JSON];  
	  		
	        return $behaviors;  
	
	    }

	    //如果未经过验证，则返回错误提示
	    public function beforeAction(){
	    	$request = Yii::$app->request;
	    	if(empty(User::accessAble($request->get('access-token')))){
	    		$response = array(
	    			 "name" => "Unauthorized",
					  "message" => "Your request was made with invalid credentials.",
					  "code" => 0,
					  "status" => 401,
					  "type" => "yii\\web\\UnauthorizedHttpException"
	    		);
	    		echo json_encode($response);
				   
	    	}else {
	    		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			    return [
			        'message' => 'result set',
			        'code' => 200,
			    ];
	    	}
	    }

	    
	}