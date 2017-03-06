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
class SettingsController extends Controller
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


    public function actionIndex(){
        return $this->actionUpdate(1);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->load(Yii::$app->request->post());
        $model->save();
        return $this->render('update', [
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
