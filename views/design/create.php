<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Design */

$this->title = 'Новый рисунок';
$this->params['breadcrumbs'][] = ['label' => 'Designs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="design-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
