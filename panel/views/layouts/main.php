<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use dmstr\web\AdminLteAsset;
use yii\helpers\Url;
use common\models\Client;
use common\models\Agency;
use common\models\Company;
use common\models\Product;
use common\models\Session;

AdminLteAsset::register($this);

$this->registerLinkTag([
	'rel'=>'icon',
	'type'=>'image/png',
	'href'=>'/img/favicon.png',
]);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-red sidebar-mini">
<?php $this->beginBody() ?>

    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="<?= Url::toRoute(['/']) ?>" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">C<b>P</b></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">Control<b>Panel</b></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="/images/default-avatar.png" class="user-image" alt="User Image">
                                <span class="hidden-xs"><?= (!Yii::$app->user->isGuest)?Yii::$app->user->identity->username:'<em>Guest</em>' ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="/images/default-avatar.png" class="img-circle" alt="User Image">
                                    <p><?= (!Yii::$app->user->isGuest)?Yii::$app->user->identity->username:'<em>Guest</em>' ?></p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <?= Html::a(Yii::t('panel/layout', 'Profile'), ['site/login'], ['class' => 'btn btn-default btn-flat']) ?>
                                    </div>
                                    <div class="pull-right">
                                        <?= Html::a(Yii::t('panel/layout', 'Log Off'), ['site/logout'], ['class' => 'btn btn-default btn-flat', 'data' => ['method' => 'post']]) ?>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Quick links -->
                        <li>
                            <?= Html::a('<i class="fa fa-lg fa-home"></i>', Yii::$app->frontendUrlManager->createAbsoluteUrl('/', true), ['target' => '_blank']) ?>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                        <li>
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- =============================================== -->

        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="/images/default-avatar.png" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?= (!Yii::$app->user->isGuest)?Yii::$app->user->identity->username:'<em>Guest</em>' ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <!-- search form -->
                <form action="#" method="get" class="sidebar-form">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="<?= Yii::t('panel/layout', 'Search...') ?>">
                        <span class="input-group-btn">
                            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </form>
                <!-- /.search form -->
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li class="header">CONTROL PANEL</li>
                    <li>
                        <a href="<?= Url::toRoute(['/']) ?>"><i class="fa fa-dashboard"></i> <span><?= Yii::t('panel/layout', 'Dashboard') ?></span></a>
                    </li>
                    <li>
                        <a href="<?= Url::toRoute(['/']) ?>"><i class="fa fa-circle-o"></i> <span>Menu Item 1</span></a>
                    </li>
                    <li>
                        <a href="<?= Url::toRoute(['/']) ?>"><i class="fa fa-circle-o"></i> <span>Menu Item 2</span></a>
                    </li>
                    <li class="header">MENU HEADER</li>
                    <li>
                        <a href="<?= Url::toRoute(['/']) ?>"><i class="fa fa-circle-o"></i> <span>Menu Item 3</span></a>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span>Menu Item 4</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?= Url::toRoute(['/']) ?>"><i class="fa fa-circle"></i> SubMenu 4.1</a></li>
                            <li><a href="<?= Url::toRoute(['/']) ?>"><i class="fa fa-circle"></i> SubMenu 4.2</a></li>
                            <li><a href="<?= Url::toRoute(['/']) ?>"><i class="fa fa-circle"></i> SubMenu 4.3</a></li>
                        </ul>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1><?= $this->title ?></h1>
                <?= Breadcrumbs::widget([
                    'homeLink' => [
                        'label'     => '<i class="fa fa-dashboard"></i> Escritorio',
                        'url'       => ['/backend/dashboard'],
                        'encode'    => false,
                    ],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
            </section>

            <!-- Main content -->
            <section class="content">

                <?= $content; ?>

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        
        <!-- =============================================== -->

        <footer class="main-footer">
            <strong><?= Yii::$app->name ?> &copy; <?= date('Y') ?></strong> Versión <?= Yii::$app->version ?>
            <em class="pull-right"><?= Yii::powered() ?></em>
        </footer>
        
        <!-- =============================================== -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li class="active"><a href="#control-sidebar-options-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                
                <!-- Stats tab content -->
                <div class="tab-pane active" id="control-sidebar-options-tab">
                    <h4>Right Menu</h4>
                    <?= Html::a('Menu Option', Url::current(), []) ?>
                </div>
                <!-- /.tab-pane -->
            </div>
        </aside>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
        immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
