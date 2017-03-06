
<script src="/js/dropzone.js"></script>
<link href="/css/dropzone.css" type="text/css" rel="stylesheet" />

<script>

function makePhotoSet(id) {
  $.ajax({
    url: 'http://'+window.location.hostname+'/color/photo-set?id='+id,
    success: function(data){
      	$('.results').html(data);
    }
  });  
}


function makePhotoDel(id) {
    if (confirm("Удалить фотографию?")) {
        $.ajax({
            url: 'http://'+window.location.hostname+'/color/photo-del?id='+id,
            success: function(data){
                window.location.reload();
                //$('.results').html(data);
            }
        });
    }
}

</script>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\ColorInput;

/* @var $this yii\web\View */
/* @var $model app\models\Color */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="row">
    <div class="col-sm-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>Иконка</h4> 
                <div class="row">
                    <div class="col-md-6">
                        <form action="/color/uploadimage/?id=<?php echo $model->id; ?>"class="dropzone"></form>                             
                    </div>
                    <div class="col-md-6">
                        <?php
                        	// $dirImageColors = \Yii::$app->params['dirImageColors'];
                       	 	$fileImage = "/images/colors/".$model->id.'.png';
                            if ($model->isImage){
                                echo Html::a(
                                        Html::img($fileImage, [ 'class' => 'img-responsive']),
                                        $fileImage
                                    );
                                echo '<br>';
                                echo '<button class="btn btn-danger" onclick="makePhotoDel('.$model->id.')">Удалить</button>';                            
                            }
                        ?>  
                    </div>                        
                </div>                
            </div>
        </div>           
    </div>
</div>


<div class="color-form">

    <?php $form = ActiveForm::begin(); ?>


	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
		</div>

		<div class="col-md-4">
		    <?php
				echo '<label class="control-label">Цвет</label>';
				echo ColorInput::widget([
				    'model' => $model,
				    'attribute' => 'code',
				]);    
			?>
		</div>	

		<div class="col-md-1">
            <?= $form->field($model, 'sort')->textInput(['size'=>2,  'maxlength' => 2]) ?>
		</div>	
	</div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
