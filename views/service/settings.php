<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Store;
use app\models\Manufacturer;

/* @var $this yii\web\View */
/* @var $model app\models\Settings */

$this->title = 'Изменить настройки: ';
$this->params['breadcrumbs'][] = ['label' => 'настройки', 'url' => ['index']];
?>
<div class="settings-update">

    <h1><?= Html::encode($this->title) ?></h1>

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
			<div class="col-md-2">
			    <?php
			        $items = Manufacturer::getList();
			        $params = [
			            'prompt' => 'Выберите пустого производителя...',
			        ];
			        echo $form->field($model, 'idManufacturerEmpty')->dropDownList($items, $params);   
			    ?>     			
			</div>			
			<div class="col-md-2">
	            <?php
	                $items = [1 => 'да', 0 => 'нет'];
	                echo $form->field($model, 'isShowCatKit')->dropDownList($items);    
	            ?>			  			
			</div>		
			<div class="col-md-2">
            	<?= $form->field($model, 'fabricExpire')->textInput(['maxlength' => true]) ?>   		  			
			</div>	
			<div class="col-md-2">
            	<?= $form->field($model, 'fabricEnd')->textInput(['maxlength' => true]) ?>   		  			
			</div>						
		</div>

	    <div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? '' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>

