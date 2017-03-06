

<style>
/*    input[type="checkbox"] {
        display:none;
    }
    input[type="checkbox"] + label span {
        display:inline-block;
        width:19px;
        height:19px;
        margin:-1px 4px 0 0;
        vertical-align:middle;
        background:url(check_radio_sheet.png) left top no-repeat;
        cursor:pointer;
    }
    input[type="checkbox"]:checked + label span {
        background:url(check_radio_sheet.png) -19px top no-repeat;
    }   */ 
</style>

<script src="/js/dropzone.js"></script>
<link href="/css/dropzone.css" type="text/css" rel="stylesheet" />

<script>

function makePhotoSet(idP, idC, idImage) {
  $.ajax({
    url: 'http://'+window.location.hostname+'/product/photo-set?idP='+idP+'&idC='+idC+'&idImage='+idImage,
    success: function(data){
      $('.results').html(data);
    }
  });  
}


function makePhotoDel(idP, idImage) {
    if (confirm("Удалить фотографию?")) {
        $.ajax({
            url: 'http://'+window.location.hostname+'/product/photo-del?idP='+idP+'&idImage='+idImage,
            success: function(data){
                window.location.reload();
                //$('.results').html(data);
            }
        });
    }
}


function saveColors(idP){
    var $box = $('.color-checkbox');
    var res;
    var values = [];

    $box.filter(':checked').each(function() {
      values.push(this.value);
    });

    $res = values.join(',');
    // $("#product-colors").val($res);
    $('#myModalColor').modal('hide');
    $.ajax({
        url: 'http://'+window.location.hostname+'/product/color-set?idP='+idP+'&colors='+$res,
        success: function(data){
          $('#colorsShow').html(data);
        }
    });    
}


function saveDesigns(idP){
    var $box = $('.design-checkbox');
    var res;
    var values = [];

    $box.filter(':checked').each(function() {
      values.push(this.value);
    });

    $res = values.join(',');
    // $("#product-colors").val($res);
    $('#myModalDesign').modal('hide');
    $.ajax({
        url: 'http://'+window.location.hostname+'/product/design-set?idP='+idP+'&designs='+$res,
        success: function(data){
          $('#designsShow').html(data);
        }
    });    
}


function saveLabel(idP){
    //var $box = $('.label-radio');
    var res;
    var values = [];

    // $box.filter(':checked').each(function() {
    //   values.push(this.value);
    // });

    // $res = values.join(',');
    $res = $('input[name=radioLabel]:checked').val();
    // $("#product-colors").val($res);
    $('#myModalLabels').modal('hide');
    $.ajax({
        url: 'http://'+window.location.hostname+'/product/label-set?idP='+idP+'&label='+$res,
        success: function(data){
          $('#labelsShow').html(data);
        }
    });    
}


window.onload = function() {
    $.ajax({
        url: 'http://'+window.location.hostname+'/product/color-get?idP='+<?= $model->id; ?>,
        success: function(data){
          $('#colorsShow').html(data);
        }
    });       
    $.ajax({
        url: 'http://'+window.location.hostname+'/product/design-get?idP='+<?= $model->id; ?>,
        success: function(data){
          $('#designsShow').html(data);
        }
    });   
    $.ajax({
        url: 'http://'+window.location.hostname+'/product/label-get?idP='+<?= $model->id; ?>,
        success: function(data){
          $('#labelsShow').html(data);
        }
    });        
}


</script>


<?php
// background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAMCAIAAADZF8uwAAAAGUlEQVQYV2M4gwH+YwCGIasIUwhT25BVBADtzYNYrHvv4gAAAABJRU5ErkJggg==);
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Category;
use app\models\KitCategory;
use app\models\Manufacturer;
use app\models\Color;
use app\models\Design;
use app\models\Label;
use app\models\Country;
?>


<?php //echo $model->id."-"; ?>

    <div class="row">
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>Главное фото</h4> 
                    <div class="row">
                        <div class="col-md-6">
                            <form action="/product/uploadmain/?id=<?php echo $model->id; ?>"class="dropzone"></form>                             
                        </div>
                        <div class="col-md-6">
                            <?php
                                if ($model->isImageMain){
                                    echo Html::a(
                                            Html::img(Yii::getAlias('@web').'/images/p'. $model->id.'/0.jpg',[ 'class' => 'img-responsive']),
                                            Yii::getAlias('@web').'/images/p'. $model->id.'/0.jpg'
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
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>Дополнительное фото 1</h4> 
                    <div class="row">
                        <div class="col-md-6">
                            <form action="/product/uploadmore/?id=<?php echo $model->id; ?>"class="dropzone"></form>                             
                        </div>
                        <div class="col-md-6">
                            <?php
                                if ($model->isImageMore){
                                    echo Html::a(
                                            Html::img(Yii::getAlias('@web').'/images/p'. $model->id.'/1.jpg',[ 'class' => 'img-responsive']),
                                            Yii::getAlias('@web').'/images/p'. $model->id.'/1.jpg'
                                        );                       
                                    // echo Html::img(Yii::getAlias('@web').'/images/p'. $model->id.'/0.jpg',['width' => '150px']);       
                                    echo '<br>';
                                    echo '<button class="btn btn-danger" onclick="makePhotoDel('.$model->id.', 1)">Удалить</button>';                                                                
                                }
                            ?>                             
                        </div>                        
                    </div>                
                </div>
            </div>           
        </div>
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>Дополнительное фото 2</h4> 
                    <div class="row">
                        <div class="col-md-6">
                            <form action="/product/uploadmore2/?id=<?php echo $model->id; ?>"class="dropzone"></form>                             
                        </div>
                        <div class="col-md-6">
                            <?php
                                if ($model->isImageMore2){
                                    echo Html::a(
                                            Html::img(Yii::getAlias('@web').'/images/p'. $model->id.'/2.jpg',[ 'class' => 'img-responsive']),
                                            Yii::getAlias('@web').'/images/p'. $model->id.'/2.jpg'
                                        );                       
                                    // echo Html::img(Yii::getAlias('@web').'/images/p'. $model->id.'/0.jpg',['width' => '150px']);       
                                    echo '<br>';
                                    echo '<button class="btn btn-danger" onclick="makePhotoDel('.$model->id.', 2)">Удалить</button>';                                                                
                                }
                            ?>                             
                        </div>                        
                    </div>                
                </div>
            </div>           
        </div>
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>Дополнительное фото 3</h4> 
                    <div class="row">
                        <div class="col-md-6">
                            <form action="/product/uploadmore3/?id=<?php echo $model->id; ?>"class="dropzone"></form>                             
                        </div>
                        <div class="col-md-6">
                            <?php
                                if ($model->isImageMore3){
                                    echo Html::a(
                                            Html::img(Yii::getAlias('@web').'/images/p'. $model->id.'/3.jpg',[ 'class' => 'img-responsive']),
                                            Yii::getAlias('@web').'/images/p'. $model->id.'/3.jpg'
                                        );                       
                                    // echo Html::img(Yii::getAlias('@web').'/images/p'. $model->id.'/0.jpg',['width' => '150px']);       
                                    echo '<br>';
                                    echo '<button class="btn btn-danger" onclick="makePhotoDel('.$model->id.', 3)">Удалить</button>';                                                                
                                }
                            ?>                             
                        </div>                        
                    </div>                
                </div>
            </div>           
        </div>                         
    </div>


<div class="form-group">
    <?php
        // if ($model->idParent == 0){
        //     $idP = 0;
        //     $idC = $model->id;            
        // }else{
        //     $idP = $model->idParent;
        //     $idC = $model->id;             
        // }
        $idP = $model->idParent;
        $idC = $model->id; 
    ?>
<!--     <button class="btn btn-primary" onclick="makePhotoSet(<?= $idP; ?>, <?= $idC; ?>, 0)">Главное фото</button> 
    <button class="btn btn-info" onclick="makePhotoSet(<?= $idP; ?>, <?= $idC; ?>, 1)">Дополнительное фото</button>        
    <button class="btn btn-danger" onclick="makePhotoSet(-1, -1, -1)">Нефотографировать</button> -->
    <?php
        if (!$model->isNewRecord){
            if ($model->idParent == 0){
                echo Html::a('Потомки', ['/product/index/?idParent='.$model->id], ['class'=>'btn btn-success']);
            }else{
                echo Html::a('Родитель', ['/product/update/?id='.$model->idParent], ['class'=>'btn btn-success']);
            }       
        } 
    ?>      
</div>     

<div class="product-form">
    <?php $form = ActiveForm::begin(
        // [
        //     'options' => [
        //         'class' => 'dropzone'
        //      ]
        // ]
    ); ?>





    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'nameYandex')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'seoURL')->textInput(['maxlength' => true]) ?>  

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>                                     
            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?> 

            <div class="col-md-6">
                <?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'content2')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'id1C')->textInput() ?>

            <?php
                $items = Category::getList();
                $params = [
                    'prompt' => 'Выберите категорию...',
                ];
                echo $form->field($model, 'idCategory')->dropDownList($items, $params);    
            ?>    

            <?php
                $items = Manufacturer::getList();
                $params = [
                    'prompt' => 'Выберите производителя...',
                ];
                echo $form->field($model, 'idManufacturer')->dropDownList($items, $params);   
            ?>  

            <?= $form->field($model, 'hook')->textInput() ?>

            <?= $form->field($model, 'density_knitting')->textInput() ?>
        </div>        

        <div class="col-md-2">
            <?php
                $items = Country::getList();
                $params = [
                    'prompt' => 'Выберите страну...',
                ];
                echo $form->field($model, 'idCountry')->dropDownList($items, $params);   
            ?>             

            <?= $form->field($model, 'rack')->textInput(['maxlength' => true]) ?>  

            <?= $form->field($model, 'colorName')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'colorCode')->textInput() ?>     

            <?= $form->field($model, 'spokes')->textInput() ?>  

            <?= $form->field($model, 'idCompanion')->textInput() ?>                                 
        </div>     
    </div>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'dttm_create')->textInput() ?>

            <?= $form->field($model, 'dttm_modify')->textInput() ?>   
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'price')->textInput() ?>

            <?php
                $items = [1 => 'да', 0 => 'нет'];
                echo $form->field($model, 'isNew')->dropDownList($items);    
            ?>             
        </div>

        <div class="col-md-2">
            <?php
                $items = [1 => 'да', 0 => 'нет'];
                echo $form->field($model, 'isReady')->dropDownList($items);    
            ?>

            <?php
                $items = [1 => 'да', 0 => 'нет'];
                echo $form->field($model, 'isArchive')->dropDownList($items);    
            ?>            
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'season')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'purpose')->textInput(['maxlength' => true]) ?>        
        </div> 

        <div class="col-md-2">
            <?= $form->field($model, 'weight')->textInput() ?>

            <?= $form->field($model, 'length')->textInput() ?>
        </div>  

        <div class="col-md-2">
            <?= $form->field($model, 'weight_unit')->textInput() ?> 
            
            <?= $form->field($model, 'length_unit')->textInput() ?>                      
        </div>        
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'tech')->textInput() ?>

            <?= $form->field($model, 'floss')->textInput() ?>
        </div>    

        <div class="col-md-2">
            <?= $form->field($model, 'canva')->textInput() ?>

            <?= $form->field($model, 'needle')->textInput() ?>
        </div>   

        <div class="col-md-2">
            <?= $form->field($model, 'language')->textInput() ?>

            <?= $form->field($model, 'colorCount')->textInput() ?>
        </div>  

        <div class="col-md-2">
            <?= $form->field($model, 'size')->textInput() ?>

            <?php
                $items = KitCategory::getList();
                $params = [
                    'prompt' => 'Выберите категорию...',
                ];
                echo $form->field($model, 'idKitCategory')->dropDownList($items, $params);    
            ?>    
        </div>      

        <div class="col-md-2">
            <!-- Button trigger modal -->
            <div id="colorsShow"></div> 
            <br>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalColor">
              Выбрать цвета
            </button>

            <!-- Modal Color -->
            <div class="modal fade" id="myModalColor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Выбор цветов</h4>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="valColors" id="valColors">
                    <?php
                        $colorList = Color::getList();
                        $colors = $model->colors;
                        $aColors = split(',', $colors);
                        foreach ($colorList as $c):
                    ?>
                        <label title="<?php echo $c['name']; ?>">                       
                            <div class="color-box" style="background-color:<?php echo $c['code']; ?>">
                                <input type="checkbox" 
                                <?php echo (in_array($c['id'], $aColors)) ? "checked" : ""; ?> 
                                class="color-checkbox" value="<?php echo $c['id'] ?>"  />                               
                            </div>
                        </label>   
                    <?php endforeach; ?>                                   
                    <br>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" onclick="saveColors(<?= $model->id; ?>)">Выбрать</button>
                  </div>
                </div>
              </div>
            </div>
        </div>

        <div class="col-md-2">
            <!-- Button trigger modal -->
            <div id="designsShow"></div> 
            <br>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalDesign">
              Выбрать рисунок
            </button>

            <!-- Modal Design -->
            <div class="modal fade" id="myModalDesign" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Выбор рисунков</h4>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="valColors" id="valColors">
                    <?php
                        $designList = Design::getList();
                        $designs = $model->designs;
                        $aDesigns = split(',', $designs);
                        foreach ($designList as $d):
                    ?>
                        <label>                     
                            <input type="checkbox" 
                            <?php echo (in_array($d['id'], $aDesigns)) ? "checked" : ""; ?> 
                            class="design-checkbox" value="<?php echo $d['id'] ?>"  />  
                            <?php echo $d['name']; ?>                              
                        </label>
                    <?php endforeach; ?>                                   
                    <br>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" onclick="saveDesigns(<?= $model->id; ?>)">Сохранить</button>
                  </div>
                </div>
              </div>
            </div>

        </div>     



        <div class="col-md-2">
            <!-- Button trigger modal -->
            <div id="labelsShow"></div> 
            <br>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalLabels">
              Выбрать метку
            </button>

            <!-- Modal Design -->
            <div class="modal fade" id="myModalLabels" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Выбор меток</h4>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="valColors" id="valColors">               
                    <?php
                        $labelList = Label::getList();
                        $labelId = $model->label;
                        foreach ($labelList as $l):
                    ?>
                        <label>                     
                            <input type="radio" 
                            <?php echo ($l['id'] == $labelId) ? "checked" : ""; ?> 
                             name="radioLabel"
                             value="<?php echo $l['id'] ?>"  />  
                            <?php echo $l['name']; ?>                              
                        </label>
                    <?php endforeach; ?>                                   
                    <br>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" onclick="saveLabel(<?= $model->id; ?>)">Сохранить</button>
                  </div>
                </div>
              </div>
            </div>

        </div>     
   
    </div>

    <div class="row">
        <div class="col-md-2">
            <?php
                $items = [1 => 'да', 0 => 'нет'];
                echo $form->field($model, 'isExportMarket')->dropDownList($items);    
            ?>
        </div>        
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


