<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ColorInput;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ColorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Цвета';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="color-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить цвет', ['create'], ['class' => 'btn btn-success']) ?>
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
                        '/color/update/?id='.$data->id
                    );
                }
            ],              
            [
                'attribute' => 'code',
                'format' => 'raw',
                'value' => function($data){
                    return ColorInput::widget([
                        'name' => 'code',
                        'value' => $data['code'],
                        'disabled' => true
                    ]);                    
                }
            ], 
            'sort',
           [
                'header' => 'Иконка',
                'format' => 'html',    
                'filter' => [0 => 'нет', 1 => 'есть'],                  
                'value' => function ($data) {
                    $str = '';
                    if ($data['isImage']){
                        $str = Html::img(Yii::getAlias('@web').'/images/colors/'. $data['id'].'.png',
                                ['width' => '30px']);  
                    }
                    return $str;                                                                                          
                    //return Html::a($str, '/product/update/?id='.$data->id);                                                 
                },
            ],            
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}{link}',
            ], 
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
