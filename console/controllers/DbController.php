<?php
namespace console\controllers;


use yii\console\Controller;

class DbController extends Controller
{
    public function actionInit()
    {
        $start = microtime(true);
        $this->stdout("Database initialization.\n");
        $this->stdout("This process will insert base data into database.\n");
        if (!$this->confirm("Continue? ")) {
            return 0;
        }
        
        $this->stdout("Initializing authentication items... ");
        $auth = \Yii::$app->getAuthManager();
        $role = $auth->createRole("admin");
        try {
            $auth->add($role);
            $this->stdout("done.\n");
        } catch (\Exception $e) {
            $this->stdout("skip.\n");
        }
        
        $elapsed_time = \Yii::$app->formatter->asDecimal(microtime(true) - $start, 3);
        $this->stdout("Completed. (took {$elapsed_time} seconds)\n");
    }
}
