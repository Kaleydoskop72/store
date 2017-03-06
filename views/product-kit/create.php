<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProductKit */

$this->title = 'Create Product Kit';
$this->params['breadcrumbs'][] = ['label' => 'Product Kits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-kit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
