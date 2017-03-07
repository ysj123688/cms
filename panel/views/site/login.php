<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('panel', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-box">
    <div class="login-logo">
        <a href="<?= Url::toRoute(['/']) ?>">Control<b>Panel</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"><?= Yii::t('panel', "Please fill out the following fields to login:") ?></p>

        <?php $form = ActiveForm::begin([]) ?>
            <div class="form-group has-feedback">
                <?= Html::activeInput('email', $model, 'email', ['placeholder' => Yii::t('panel', 'Email'), 'class' => 'form-control']) ?>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <?= Html::activeInput('password', $model, 'password', ['placeholder' => Yii::t('panel', 'Password'), 'class' => 'form-control']) ?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <?= $form->field($model, 'rememberMe')->checkbox([]) ?>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <?= Html::submitButton(Yii::t('panel', 'Submit'), ['class' => 'btn btn-primary btn-block btn-flat'])?>
                </div>
                <!-- /.col -->
            </div>
        <?php ActiveForm::end() ?>

        <p class="text-center">- <?= Yii::t('panel', 'Or') ?> -</p>
        <?= Html::a(Yii::t('panel', 'Go back to Home page'), Yii::$app->frontendUrlManager->createAbsoluteUrl('/', true)) ?><br>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->