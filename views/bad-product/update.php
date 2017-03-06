<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BadProduct */

$this->title = 'Update Bad Product: ' . $model->idProduct;
$this->params['breadcrumbs'][] = ['label' => 'Bad Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idProduct, 'url' => ['view', 'id' => $model->idProduct]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bad-product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
