<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $model app\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>


<script src="/js/dropzone.js"></script>
<link href="/css/dropzone.css" type="text/css" rel="stylesheet" />

<script>

// function makePhotoSet(idC, idImage) {
//   $.ajax({
//     url: 'http://'+window.location.hostname+'/category/photo-set?idC='+idC+'&idImage='+idImage,
//     success: function(data){
//       $('.results').html(data);
//     }
//   });  
// }


function makePhotoDel(idC, idImage) {
    if (confirm("Удалить фотографию?")) {
        $.ajax({
            url: 'http://'+window.location.hostname+'/category/photo-del?idC='+idC+'&idImage='+idImage,
            success: function(data){
                window.location.reload();
                //$('.results').html(data);
            }
        });
    }
}


</script>


<div class="row">
    <div class="col-sm-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>Фото</h4> 
                <div class="row">
                    <div class="col-md-6">
                        <form action="/category/uploadmain/?id=<?php echo $model->id; ?>"class="dropzone"></form>                             
                    </div>
                    <div class="col-md-6">
                        <?php
                            if ($model->isImage){
                                echo Html::a(
                                        Html::img(Yii::getAlias('@web').'/images/c'. $model->id.'/0.jpg',[ 'class' => 'img-responsive']),
                                        Yii::getAlias('@web').'/images/c'. $model->id.'/0.jpg'
                                    );  
                                echo '<br>';
                                echo '<button class="btn btn-danger" onclick="makePhotoDel('.$model->id.', 0)">Удалить</button>';                            
                            }
                        ?>  
                    </div>                        
                </div>                
            </div>
        </div>           
    </div>
</div>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">

        <div class="col-md-8">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            
            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>       

            <?= $form->field($model, 'nameCategory')->textInput(['maxlength' => true]) ?> 

            <?= $form->field($model, 'seoURL')->textInput(['maxlength' => true]) ?>  

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>                                    
        </div>            
        <div class="col-md-4">
            <?php
                $items = Category::getList();
                $params = [
                    'prompt' => 'Выберите категорию...',
                ];
                echo $form->field($model, 'idParent')->dropDownList($items, $params);    
            ?>  
          
            <?php
                $items = Category::getListType();
                echo $form->field($model, 'typeProduct')->dropDownList($items);    
            ?>   
            <?php
                $items = [1 => 'да', 0 => 'нет'];
                echo $form->field($model, 'isReady')->dropDownList($items);    
            ?> 
            <?php
                $items = [1 => 'да', 0 => 'нет'];
                echo $form->field($model, 'isProduct')->dropDownList($items);    
            ?>             
            <?= $form->field($model, 'idProduct')->textInput() ?>             
        </div>    
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
