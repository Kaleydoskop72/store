<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductColorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товар по цветам';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['/product/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-color-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить цвет', ['create', 'product_id' => $product_id], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id1C',
            'sku',            
            [
                'attribute' => 'colorName',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(
                        $data->colorName,
                        '/product-color/update/?id='.$data->id
                    );
                }
            ],                                              
            'location',            
            [
                'attribute' => 'isImageMain',
                'header' => 'Главное фото',
                'format' => 'html',    
                'value' => function ($data) {
                    if ($data['isImageMain']){
                        return Html::img(Yii::getAlias('@web').'/images/p'. $data['idProduct'].'/c'.$data['id'].'_0.jpg',
                            ['width' => '90px']);                        
                    }else{
                        return '';
                    }
                },
            ],
          [
                'attribute' => 'isImageMore',
                'header' => 'Подробное фото',
                'format' => 'html',    
                'value' => function ($data) {
                    if ($data['isImageMore']){
                        return Html::img(Yii::getAlias('@web').'/images/p'. $data['idProduct'].'/c'.$data['id'].'_1.jpg',
                            ['width' => '90px']);                        
                    }else{
                        return '';
                    }
                },
            ],  
            'comment',          
            'price',          
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}{link}',
        ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
