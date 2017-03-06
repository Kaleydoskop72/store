<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\KitCategory */

$this->title = 'Новая категория';
$this->params['breadcrumbs'][] = ['label' => 'Категории для наборов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kit-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
