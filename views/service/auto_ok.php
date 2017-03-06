<div class="content">
<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Product;
use app\models\AutoForm;

$this->title = 'Автомат';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if ($model->mode == AutoForm::FIND_READY): ?>
	<h3>Товары обработаны</h3>
	отчет об операции: <b>Отчеты\Неготовые товары</b>, и <b>Товары</b>
<?php endif; ?>

<?php if ($model->mode == AutoForm::CLEAN_IM): ?>
	<h3>Очистка запуститься в течении 1-й минуты</h3>
	отчет об операции: <b>Отчеты\Журнал</b>
<?php endif; ?>

<?php if ($model->mode == AutoForm::EXPORT_2_IM): ?>
	<h3>Экспорт запуститься в течении 1-й минуты</h3>
	отчет об операции: <b>Отчеты\Экспорт в магазин</b>, и <b>Отчеты\Журнал</b>
<?php endif; ?>

</div>
