<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Store */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="store-form">

    <?php $form = ActiveForm::begin(); ?>

	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-md-2">
			<?php
                $items = [1 => 'да', 0 => 'нет'];
                echo $form->field($model, 'isActive')->dropDownList($items); 
            ?>
		</div>
		<div class="col-md-2">
			<?php
                $items = [1 => 'да', 0 => 'нет'];
                echo $form->field($model, 'isMain')->dropDownList($items); 
            ?>
		</div>
		<div class="col-md-2">
			<?= $form->field($model, 'order')->textInput(['maxlength' => true]) ?>
		</div>			
	</div>
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
