<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\StoreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Склады';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить склад', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(
                        $data->name,
                        '/store/update/?id='.$data->id
                    );
                }
            ],   
           [
                'attribute' => 'isActive',
                'format' => 'html',    
                'filter' => [0 => 'нет', 1 => 'активный'],                  
                'value' => function ($data) {
                    $str = ($data['isActive']) ? '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>' : ''; 
                    return $str;                                                                                                                     
                },
            ],     
           [
                'attribute' => 'isMain',
                'format' => 'html',    
                'filter' => [0 => 'нет', 1 => 'главный'],
                'value' => function ($data) {
                    $str = ($data['isMain']) ? '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>' : ''; 
                    return $str;                                                                                                                     
                },
            ],               
            'order',               
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}{link}',
            ], 
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
