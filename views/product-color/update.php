<script>

function makePhoto(mode, id) {
  $.ajax({
    url: 'http://'+window.location.hostname+'/product/photo?mode='+mode+'&id='+id+'&filename=',
    success: function(data){
      $('.results').html(data);
    }
  });
}

</script>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProductColor */

$this->title = 'Изменить цвет: ' . $model->colorName;
$this->params['breadcrumbs'][] = ['label' => 'Цвета', 'url' => ['index', 'product_id' => $model->idProduct]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="product-color-update">

    <h1><?= Html::encode($this->title) ?></h1>


<div class="form-group">
    <button class="btn btn-success" onclick="makePhoto(2, <?php echo $model->id; ?>)">Фотографировать</button>   
    <button class="btn btn-danger" onclick="makePhoto(0, <?php echo $model->id; ?>)">Нефотографировать</button>  
</div>    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
