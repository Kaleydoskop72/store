<div class="content">
<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\application\commands;
use yii\console\controllers\Import2Controller;

$this->title = 'Импорт из 1С';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin([
                'options' => ['enctype'=>'multipart/form-data']
        ]); ?>

<?=$form->field($model, 'importFile')->fileInput() ?>

<?=Html::submitButton('Загрузить') ?>
<?php ActiveForm::end(); ?>

</div>

<br>

<?php

function runCommand(){
	if (! defined('STDOUT')) {
	    define('STDOUT', fopen('/tmp/stdout', 'w'));
	}

	$consoleController = new \yii\console\controllers\ImportController;
	$consoleController->runAction('import2 importStart');

	$handle = fopen('/tmp/stdout', 'r');
	$message = '';
	while (($buffer = fgets($handle, 4096)) !== false) {
		$message .= $buffer . "\n";
	}
	fclose($handle);
	return $message;
}


   $res = runCommand();
   echo "---".$res."+++";
?>


<div class="row">
	<div class="progress col-md-10">
	  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
	    <span class="sr-only">60% Complete</span>
	  </div>
	</div>	
	<div class="col-md-2"></div>
</div>

