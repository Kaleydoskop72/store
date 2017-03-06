<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductColor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-color-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'colorName')->textInput(['maxlength' => true]) ?>   

    <?= $form->field($model, 'id1C')->textInput() ?>     

    <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'colorCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput() ?>    

    <?= $form->field($model, 'location')->textInput() ?>    

    <?= $form->field($model, 'comment')->textInput() ?>            

    <div class="form-group">
        <?php
            if ($model->isImageMain){
                echo "<h3>Главное изображение</h3>";
                echo Html::img(Yii::getAlias('@web').'/images/p'.$model->idProduct.'/c'.$model->id.'_0.jpg' ,['width' => '800px']);       
            }
        ?>        
    </div>

    <div class="form-group">
        <?php
            if ($model->isImageMore){
                echo "<h3>Дополнительное изображение</h3>";
                echo Html::img(Yii::getAlias('@web').'/images/p'.$model->idProduct.'/c'.$model->id.'_1.jpg' ,['width' => '800px']);       
            }
        ?>        
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
