<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductKitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Kits';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-kit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Product Kit', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'idManufacturer',
            'id1C',
            'sku',
            'name:ntext',
            // 'description:ntext',
            // 'price',
            // 'thread',
            // 'size',
            // 'sizePack',
            // 'canva',
            // 'author',
            // 'colorCount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
