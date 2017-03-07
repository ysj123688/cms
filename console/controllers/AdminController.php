<?php
namespace console\controllers;


use yii\console\Controller;
use common\models\Admin;

/**
 * Helps maintain administrators's access to this application.
 * 
 * @author Rostislav Pleshivtsev Oparina
 */
class AdminController extends Controller
{
    /**
     * Shows a list of administrators
     * @return number
     */
    public function actionIndex()
    {
        $models = Admin::find()->all();

        $this->stdout(str_pad('ID', '4', ' ', STR_PAD_RIGHT));
        $this->stdout(str_pad('Username', '32', ' ', STR_PAD_RIGHT));
        $this->stdout(str_pad('AccessToken', '40', ' ', STR_PAD_RIGHT));
        $this->stdout(str_pad('Status', '8', ' ', STR_PAD_RIGHT));
        $this->stdout(PHP_EOL);
        $this->stdout(str_pad('', '100', '-') . PHP_EOL);
        
        $key = -1;
        /** @var \common\models\Admin $model */
        foreach ($models as $key => $model) {
            $this->stdout(str_pad($model->id, '4', ' ', STR_PAD_RIGHT));
            $this->stdout(str_pad($model->username, '32', ' ', STR_PAD_RIGHT));
            $this->stdout(str_pad($model->email, '40', ' ', STR_PAD_RIGHT));
            $this->stdout(str_pad($model->user->getStatusLabel(), '8', ' ', STR_PAD_RIGHT));
            $this->stdout(PHP_EOL);
        }
        
        $key++;
        $this->stdout(str_pad('', '100', '-') . PHP_EOL);
        $this->stdout("Total: $key models" . PHP_EOL);
    }
    
    /**
     * Create a new admin.
     * @param string $username
     * @param string $email This parameter will be used to log in inside control panel.
     * @param string $password
     * @return number
     */
    public function actionCreate($username, $email, $password)
    {
        $model = new \common\models\Admin([
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ]);
        
        if ($model->save()) {
            $auth = \Yii::$app->getAuthManager();
            $role = $auth->getRole("admin");
            if ($role !== null) {
                $auth->assign($role, $model->user_id);
            } else {
                $this->stderr("Couldn't assign role 'admin' for {$username}. The role 'admin' ");
                $this->stderr("doesn't exist. Check your database or run the command ");
                $this->stderr("[php yii db/init] to create all required data.\n");
            }
            
            $this->stdout("Administrator [{$username}] was succesfuly created.\n");
            $this->stdout("Use its email ({$email}) and password to login in control panel.\n");
            return 0;
        } else {
            $this->stderr("Could not create Administrator.\n");
            $this->stderr(\yii\helpers\VarDumper::dump($model->getErrors()));
            return 1;
        }
    }
    
    /**
     * Delete an admin.
     * @param string $email Email that uniquely identifies administrators.
     * @return number
     */
    public function actionDelete($email)
    {
        $model = \common\models\Admin::findOne(['email' => $email]);
        
        if ($model === null) {
            $this->stderr("Sorry, there is no administrator with this email ({$email}).\n");
            return 1;
        }
        
        if (!$this->confirm("Are you sure you want to delete this administrator? ")) {
            return 0;
        }
        
        if ($model->delete() !== false) {
            $this->stdout("Administrator {$email} was deleted.\n");
            return 0;
        } else {
            $this->stderr("Couldn't delete administrator {$email}.\n");
            $this->stderr(\yii\helpers\VarDumper::dump($model->getErrors()));
        }
    }
    
    /**
     * Activates an administrator which enables it's login
     * @param integer $id Administrator's model ID
     * @return number Exit code
     */
    public function actionActivate($id)
    {
        $model = \common\models\Admin::findOne($id);
        
        if ($model === null) {
            $this->stderr("Sorry, could not find administrator with this ID.\n");
            return 1;
        }
        
        if ($model->user->isActive()) {
            $this->stdout("This administrator ({$model->username}) is already active .\n");
            return 0;
        } else {
            if ($model->user->activate() !== false) {
                $this->stdout("Administrator ({$model->username}) has been activated.\n");
            }
        }
        
        return 0;
    }
    
    /**
     * Suspends an administrator, which will disable it's login
     * @param integer $id Administrator's model ID
     * @return number Exit code
     */
    public function actionSuspend($id)
    {
        $model = \common\models\Admin::findOne($id);
        
        if ($model === null) {
            $this->stderr("Sorry, could not find administrator with this ID.\n");
            return 1;
        }
        
        if ($model->user->isSuspended()) {
            $this->stdout("This administrator ({$model->username}) is already suspended .\n");
            return 0;
        } else {
            if ($model->user->suspend() !== false) {
                $this->stdout("Administrator ({$model->username}) has been suspended.\n");
            }
        }
        
        return 0;
    }
}
