<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Store;

/* @var $this yii\web\View */
/* @var $model app\models\Settings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="settings-form">

    <?php $form = ActiveForm::begin(); ?>

	<div class="row">
		<div class="col-md-2">
		    <?php
		        $items = Store::getList();
		        $params = [
		            'prompt' => 'Выберите склад...',
		        ];
		        echo $form->field($model, 'idStore')->dropDownList($items, $params);   
		    ?>     			
		</div>
	</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
