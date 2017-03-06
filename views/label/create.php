<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Label */

$this->title = 'Новая метка';
$this->params['breadcrumbs'][] = ['label' => 'Метки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="label-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
