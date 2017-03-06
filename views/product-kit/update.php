<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProductKit */

$this->title = 'Update Product Kit: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Product Kits', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-kit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
