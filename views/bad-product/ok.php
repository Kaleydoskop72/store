<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BadProduct */

$this->title = 'Товары с ошибками';
$this->params['breadcrumbs'][] = ['label' => 'Товары с ошибками', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bad-product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <h3>Отчет сформирован</h3>
    </p>


</div>
