<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?
$goToHomeParam = Yii::$app->response->statusCode != 200 ? [
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
] : [];
$navParams = [
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
];
$navParams += $goToHomeParam;
?>

<div class="wrap">
    <? if (!Yii::$app->user->isGuest): ?>
        <?php
        NavBar::begin($navParams);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                [
                    'label' => Yii::$app->user->identity->getName(),
                    'options' => ['id'=>'user-id'],
                    'items' => [
                        ['label' => 'Edit profile', 'url' => '#',
                            'options' =>
                                [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#editProfile',
                                ]
                        ],
                        ['label' => 'Logout', 'url' => ['site/logout']],
                    ]

                ]
            ],
        ]);
        NavBar::end();
        ?>
    <?php endif; ?>

    <div class="container">
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
