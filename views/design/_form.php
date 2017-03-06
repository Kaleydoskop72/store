<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Design */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="design-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
    	<div class="col-md-10">
    		<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>		
    	</div>
		<div class="col-md-4">
		    <?php
		        $items = [1 => 'да', 0 => 'нет'];
		        echo $form->field($model, 'isShow')->dropDownList($items);    
	    	?>  	
		</div>    	
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
