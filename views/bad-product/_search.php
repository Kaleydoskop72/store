<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $model app\models\BadProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="bad-product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
<div class="row">
    <div class="col-md-2">
        <?php
            $items = Category::getList();
            $params = [
                'prompt' => 'Выберите категорию...',
            ];
            echo $form->field($model, 'idCategory')->dropDownList($items, $params);    
        ?>          
    </div>

    <div class="col-md-2">
        <?= $form->field($model, 'idProduct')->textInput() ?>    
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'id1C')->textInput() ?>    
    </div>    
</div>
<div class="row">
    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>
</div>
    <?php ActiveForm::end(); ?>
    
</div>

