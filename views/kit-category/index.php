<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\KitCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории для наборов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kit-category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
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
                        '/kit-category/update/?id='.$data->id
                    );
                }
            ],             
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}{link}',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
