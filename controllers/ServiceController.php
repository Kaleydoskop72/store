<?php

namespace app\controllers;

use Yii;
use app\models\Settings;
use app\models\Import1cForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * SettingsController implements the CRUD actions for Settings model.
 */
class ServiceController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    public function actionSettings(){
        $model = $this->findModel(1);

        $model->load(Yii::$app->request->post());
        $model->save();
        return $this->render('settings', [
            'model' => $model,
        ]);  
    }


    public function actionImport(){
        $model = new Import1cForm();

        if (Yii::$app->request->isPost) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            if ($model->upload()) {
                return 'ok';
            }
        }
        return $this->render('import1c', ['model' => $model]);
    }


    public function actionHintHandlePhoto(){
      $filename = '/data/tmp/hintHandlePhoto';
      if ($f = fopen($filename, 'w+')){
        $str = 'ok';
        fwrite($f, $str);
        fclose($f);
      }else{
        $str = 'error';
      }
      return $str;
    }


    public function actionAuto(){
        $model = new \app\models\AutoForm();

        if ($model->load(Yii::$app->request->post())) {
            $list = $model->make();
            return $this->render('auto_ok', ['model' => $model, 'list' => $list]);
        }
        // if (Yii::$app->request->isPost) {
        //     if ($model->make()){
        //         return $this->render('auto_ok', ['model' => $model]);
        //     }
        // }
        return $this->render('auto', ['model' => $model]);
    }



       // $dir = Yii::getAlias('@frontend/../web/uploads/test/');
       //  $uploaded = false;
       //  $model = new Test();
        
       //  if ($model->load($_POST)) {
       //      //$file = UploadedFile::getInstances($model, 'image');
       //      $file = UploadedFile::getInstance($model, 'image');
            
       //      $model->image = $file;
            
       //      if ($model->validate()) {
       //          $uploaded = $file->saveAs( $dir . $model->image->name );
       //      }
       //  }
        
       //  return $this->render('test',[
       //       'model' => $model,
       //       'uploaded' => $uploaded,
       //       'dir' => $dir,
       //     ]);    


    /**
     * Finds the Settings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Settings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Settings::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
