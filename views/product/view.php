<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'sku',
            'id1C',
            'idCategory',
            'idManufacturer',
            'idParent',
            'idLocate',
            'name',
            'description:ntext',
            'rack',
            'colorName',
            'colorCode',
            'isImageMain',
            'isImageMore',
            'price',
            'dttm_create',
            'dttm_modify',
            'isReady',
            'isPublished',
            'urlSrc:url',
            'comment:ntext',
            'season',
            'purpose',
            'weight',
            'weight_unit',
            'length',
            'length_unit',
        ],
    ]) ?>

</div>
