<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Category;
use app\models\KitCategory;
use app\models\Manufacturer;

/* @var $this yii\web\View */
/* @var $model app\models\ProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-2">
                <?= $form->field($model, 'id1C') ?>
        </div>         
        <div class="col-md-2">
                <?= $form->field($model, 'sku') ?>
        </div>         
        <div class="col-md-8">
                <?= $form->field($model, 'name') ?>
        </div>            
    </div>
  
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
            <?php
                $items = Manufacturer::getList();
                $params = [
                    'prompt' => 'Выберите производителя...',
                ];
                echo $form->field($model, 'idManufacturer')->dropDownList($items, $params);   
            ?>         
        </div>              
        <div class="col-md-2">
            <?php
                $items = ['' => '', 1 => 'да', 0 => 'нет'];
                echo $form->field($model, 'isReady')->dropDownList($items);    
            ?>        
        </div>      
        <div class="col-md-2">
            <?php
                $items = ['' => '', 1 => 'да', 0 => 'нет'];
                echo $form->field($model, 'isArchive')->dropDownList($items);    
            ?>            
        </div>       
        <div class="col-md-2">
            <?php
                $items = ['' => '', 1 => 'да', 0 => 'нет'];
                echo $form->field($model, 'isImageMain')->dropDownList($items);    
            ?>            
        </div>           
        <div class="col-md-2">
            <?php
                $items = ['' => '', 1 => 'да', 0 => 'нет'];
                echo $form->field($model, 'isExportMarket')->dropDownList($items);    
            ?>            
        </div>                      
    </div>

    <?php // echo $form->field($model, 'idParent') ?>

    <?php // echo $form->field($model, 'idLocate') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'rack') ?>

    <?php // echo $form->field($model, 'colorName') ?>

    <?php // echo $form->field($model, 'colorCode') ?>

    <?php // echo $form->field($model, 'isImageMain') ?>

    <?php // echo $form->field($model, 'isImageMore') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'dttm_create') ?>

    <?php // echo $form->field($model, 'dttm_modify') ?>

    <?php // echo $form->field($model, 'isReady') ?>

    <?php // echo $form->field($model, 'isPublished') ?>

    <?php // echo $form->field($model, 'urlSrc') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'season') ?>

    <?php // echo $form->field($model, 'purpose') ?>

    <?php // echo $form->field($model, 'weight') ?>

    <?php // echo $form->field($model, 'weight_unit') ?>

    <?php // echo $form->field($model, 'length') ?>

    <?php // echo $form->field($model, 'length_unit') ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
        <?php //echo Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
