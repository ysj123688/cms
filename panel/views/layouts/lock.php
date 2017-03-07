<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use dmstr\web\AdminLteAsset;

AdminLteAsset::register($this);

$this->registerLinkTag([
	'rel'=>'icon',
	'type'=>'image/png',
	'href'=>'/images/favicon.png',
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
<body class="hold-transition lockscreen">
<?php $this->beginBody() ?>

    <?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
