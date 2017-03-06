<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductKitSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-kit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idManufacturer') ?>

    <?= $form->field($model, 'id1C') ?>

    <?= $form->field($model, 'sku') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'thread') ?>

    <?php // echo $form->field($model, 'size') ?>

    <?php // echo $form->field($model, 'sizePack') ?>

    <?php // echo $form->field($model, 'canva') ?>

    <?php // echo $form->field($model, 'author') ?>

    <?php // echo $form->field($model, 'colorCount') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
