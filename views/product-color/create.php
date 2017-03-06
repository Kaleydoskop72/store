<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProductColor */

$this->title = 'Добавить цвет';
$this->params['breadcrumbs'][] = ['label' => 'Товар по цветам', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-color-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
