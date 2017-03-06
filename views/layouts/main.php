<?php

/* @var $this \yii\web\View */
/* @var $content string */

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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

<?php
$js = <<< 'SCRIPT'
/* To initialize BS3 tooltips set this below */
$(function () { 
    $("[data-toggle='tooltip']").tooltip(); 
});;
/* To initialize BS3 popovers set this below */
$(function () { 
    $("[data-toggle='popover']").popover(); 
});
SCRIPT;
// Register tooltip/popover initialization javascript
$this->registerJs($js);
?>

</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->params['siteName'],
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [

            ['label' => 'Товары', 'url' => ['/product/index']],   
            // ['label' => 'Товары от производителей',  'items' => [                
            //     ['label' => 'Наборы', 'url' => ['/product-kit/index']],               
            //]],              
            ['label' => 'Справочники', 'items' => [                
                ['label' => 'Категории', 'url' => ['/category/index']],   
                ['label' => 'Категории для наборов', 'url' => ['/kit-category/index']],                                 
                ['label' => 'Производители', 'url' => ['/manufacturer/index']],
                ['label' => 'Страны', 'url' => ['/country/index']],   
                ['label' => 'Склады', 'url' => ['/store/index']], 
                ['label' => 'Цвета', 'url' => ['/color/index']], 
                ['label' => 'Рисунки', 'url' => ['/design/index']],    
                ['label' => 'Метки', 'url' => ['/label/index']],                               
            ]],    
            ['label' => 'Отчеты', 'items' => [                
                ['label' => 'Журнал', 'url' => ['/bad-product/index']],     
                ['label' => 'Неготовые товары', 'url' => ['/bad-product/index']],             
                ['label' => 'Экспорт готовых', 'url' => ['/bad-product/index']],                                        
            ]],    
            ['label' => 'Сервис', 'items' => [                
                ['label' => 'Настройки', 'url' => ['/service/settings']],  
                ['label' => 'Выгрузка из 1С -> Mngr', 'url' => ['/service/import']],
                ['label' => 'Автомат', 'url' => ['/service/auto']],                                                
            ]],             
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy;  <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
