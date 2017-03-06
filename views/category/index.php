<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

            'id',
            'idParent',
            [
                'attribute' => 'idParent',
                'format' => 'raw',    
                'filter' => Category::getListParentFilter(),  
                'value' => function ($data){
                    if ($data['idParent'] != NULL){
                        $str = Category::getName($data['idParent']);  
                        $isShowCatKit = false;
                        return $str;                                    
                    }
                    return '';
                },
            ],             
            // [
            //     'attribute' => 'idParent',
            //     'format' => 'raw',
            //     'value' => function($data){
            //         return Category::getName($data['idParent']);
            //     }
            // ],               
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(
                        $data->name,
                        '/category/update/?id='.$data->id
                    );
                }
            ],          
            [
                'attribute' => 'typeProduct',
                'format' => 'raw',
                'value' => function($data){
                    return Category::getNameType($data['typeProduct']);
                }
            ],       
            [
                'attribute' => 'description',
                'format' => 'raw',
                'value' => function($data){
                    return substr(strip_tags($data['description']), 0, 20);
                }
            ],                            
            // 'description:ntext',
            [
                'attribute' => 'isImageMain',
                'header' => 'Фото',
                'format' => 'html',    
                'value' => function ($data) {
                    if ($data['isImage']){  
                        return Html::a(
                            Html::img(Yii::getAlias('@web').'/images/c'. $data['id'].'/0.jpg',
                                ['width' => '90px']),
                            '/category/update/?id='.$data->id
                        );                                                 
                    }else{
                        return '';
                    }
                },
            ],  
           [
                'attribute' => 'isWork',
                'format' => 'html',    
                'filter' => [0 => 'нет', 1 => 'да'],                  
                'value' => function ($data) {
                    $str = ($data['isWork']) ? '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>' : ''; 
                    return $str;                                                                                                                     
                },
            ],              
           [
                'attribute' => 'isReady',
                'format' => 'html',    
                'filter' => [0 => 'нет', 1 => 'да'],                  
                'value' => function ($data) {
                    $str = ($data['isReady']) ? '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>' : ''; 
                    return $str;                                                                                                                     
                },
            ],                
           [
                'attribute' => 'isProduct',
                'format' => 'html',    
                'filter' => [0 => 'нет', 1 => 'да'],                  
                'value' => function ($data) {
                    $str = ($data['isProduct']) ? '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>' : ''; 
                    return $str;                                                                                                                     
                },
            ],                   
            'idProduct',   
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}{link}',
        ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
