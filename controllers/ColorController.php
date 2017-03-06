<?php

namespace app\controllers;

use Yii;
use app\models\Color;
use app\models\ColorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ColorController implements the CRUD actions for Color model.
 */
class ColorController extends Controller
{

    private $dirImage;
    private $filePhotoData;
    private $fileDst; 


    function photoCheckDir($id){
        $path = $this->dirImage;        
        if (!file_exists($path)){
            mkdir($path);
        }
    }    


    public function beforeAction($action){
        $aActions = [ 'photo-do', 'uploadimage' ];
        foreach ($aActions as $a) {
            if ($action->id === $a){
                $this->enableCsrfValidation = false;
                break;
            }            
        }             
        return parent::beforeAction($action);
    }


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



    public function actionUploadimage($id){   
        if (!empty($_FILES)){
            $this->uploadPhoto($id, $_FILES['file'], 0);           
        }
    }


   public function uploadPhoto($id, $file, $numPhoto){
        $color = Color::findOne($id);    
        $this->dirImage = \Yii::$app->params['dirImageColors'];
        $this->photoCheckDir($id);       
        $tempFile = $file['tmp_name'];
        $targetFile = $this->dirImage.'/'.$id;         
        $targetFile .= ".png"; 
        move_uploaded_file($tempFile, $targetFile);        
        $color->isImage = 1;
        if (!$color->save()){
            Yii::info(print_r($product->getErrors()), 'info');
        }
    }


    public function actionPhotoDel($id){
        if ($color = Color::findOne($id)){
            $color->isImage = 0;
            $color->save();
        }
        $filename = \Yii::$app->params['dirImageColors'].'/'.$color->id.'.png';
        unlink($filename);
    }


    /**
     * Lists all Color models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ColorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Color model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Color model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Color();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->actionIndex();
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Color model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // return $this->redirect(['view', 'id' => $model->id]);
            return $this->actionIndex();            
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Color model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Color model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Color the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Color::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
