<div class="content">
<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\AutoForm;

$this->title = 'Автомат';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin([
                'options' => ['enctype'=>'multipart/form-data']
        ]); ?>

<?= $form->field($model, 'mode')->radioList(
        [
            AutoForm::IMPORT_1C 	=> 'Загрузить из 1C',        
            AutoForm::FIND_READY	=> 'Найти готовые товары', 
            AutoForm::CLEAN_IM		=> 'Очистить магазин',                               
            AutoForm::EXPORT_2_IM => 'Выгрузить готовые в магазин',
        ]
    );
?>

<?=Html::submitButton('Выполнить') ?>
<?php ActiveForm::end(); ?>


</div>
