<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\widgets\Menu;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body >
<?php $this->beginBody() ?>
<div class="container-fluid">
    <div class="row">
        <!-- Левый сайдбар -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar">
            <div class="d-flex flex-column">
                <!-- Логотип -->
                <img src="/images/logow.png" alt="Логотип">

                <!-- ФИО пользователя -->
                <h5><?= \Yii::$app->user->identity->lastname .' '.\Yii::$app->user->identity->firstname .' '.\Yii::$app->user->identity->surname ?></h5>



                <?= Menu::widget([
                'items' => [
                ['label' => 'Конкурсы', 'url' => ['/']],
                ['label' => 'Личные данные', 'url' => ['site/lk']],
                ['label' => 'Мои заявки', 'url' => ['application/index']],
                ['label' => 'Контакты', 'url' => ['site/contact']],
                ],
                    'options' => [
                        'class' => 'nav flex-column',
                    ],
                    'itemOptions'=>['class'=>'nav-item'],
                    'activeCssClass'=>'active',
                    'linkTemplate'=>'<a href="{url}" class="nav-link">{label}</a>',
                ]);
                ?>
            </div>
        </nav>

        <!-- Контентная часть -->
        <main class="col-md-9 col-lg-10 content">
            <h3><?= Html::encode($this->title) ?></h3>
            <?= $content ?>
        </main>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
