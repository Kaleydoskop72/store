<?php

namespace app\controllers;

use Yii;
use app\models\Category;
use app\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{

    private $dirImage;
    private $filePhotoData;
    private $fileDst; 

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


    public function beforeAction($action){
        $aActions = [ 'photo-do', 'uploadmain' ];
        foreach ($aActions as $a) {
            if ($action->id === $a){
                $this->enableCsrfValidation = false;
                break;
            }            
        }             
        return parent::beforeAction($action);
    }


    // function photoDataSet($idP, $idC, $idImage){
    //     $idP = ($idP == -1) ? '-' : $idP;
    //     $idC = ($idC == -1) ? '-' : $idC;        
    //     $idImage = ($idImage == -1) ? '-' : $idImage;        
    //     $fp = fopen($this->filePhotoData, 'w');
    //     fwrite($fp, $idP.";".$idC.";".$idImage);
    //     fclose($fp);
    // }
  

    // function photoDataGet(&$idP, &$idC, &$idImage){
    //     $idP = 0;
    //     $idC = 0;
    //     $idImage = 0;
    //     $handle = fopen($this->filePhotoData, "r");
    //     $str = fread($handle, filesize($this->filePhotoData));
    //     fclose($handle);        
    //     preg_match("/([-\d]+);([-\d]+);([-\d]+)/", $str, $matches); 
    //     $idP = $matches[1];
    //     $idC = $matches[2];        
    //     $idImage = $matches[3];
    //     $idP = ($idP == '-') ? NULL : $idP;
    //     $idC = ($idC == '-') ? NULL : $idC;        
    //     $idImage = ($idImage == '-') ? NULL : $idImage;         
    // }


    function photoCheckDir($id){
        $path = $this->dirImage."/c".$id;        
        if (!file_exists($path)){
            mkdir($path);
        }
    }    


    // public function actionPhotoSet($idP, $idC, $idImage){
    //     $this->dirImage      = \Yii::$app->params['dirImage'];
    //     $this->filePhotoData = $this->dirImage."/photoData.txt";
    //     $this->photoDataSet($idP, $idC, $idImage); 
    //     // if ($idP == -1 && $idC == -1){
    //     //     $this->photoDataSet(NULL, NULL, NULL);
    //     // }else{
    //     //     if ($idP != -1 && $idC == -1){
    //     //         $this->photoDataSet($idP, NULL, $idImage);
    //     //     }else{
    //     //         $this->photoDataSet($idP, $idC, $idImage);                
    //     //     }
    //     // }
    // }  


    public function actionPhotoDel($idC, $idImage){
        if ($cat = Category::findOne($idC)){
            $cat->isImage = 0;
            $cat->save();
        }
        $filename = \Yii::$app->params['dirImage'].'c'.$cat->id.'/'.$idImage.'.jpg'; 
        unlink($filename);
    }  


    // public function actionPhotoDo(){     
    //     $this->dirImage     = \Yii::$app->params['dirImage'];
    //     $this->filePhotoData = $this->dirImage."/photoData.txt";
    //     $fileTmp = $this->dirImage."/tmp.jpg";

    //     $this->photoDataGet($idP, $idC, $idImage);  
    //     if ($idImage == NULL || $idC == NULL){
    //         return '';
    //     }
    //     $this->photoCheckDir($idC);
    //     $this->fileDst = $this->dirImage;
    //     $this->fileDst .= "/p".$idC."/";        
    //     $dataFile = file_get_contents('php://input');
    //     if (!(file_put_contents($fileTmp ,$dataFile) === FALSE)){   
    //         $fileName = $idImage.".jpg";
    //         $this->fileDst = $this->fileDst.$fileName;
    //         //exec("convert $fileTmp  $this->fileDst");
    //         //
    //         // exec("convert $fileTmp -pointsize 128  -thumbnail 1024  -fill white -filter Triangle -define filter:support=2  -unsharp 0.25x0.25+8+0.065 -dither  None -posterize 136 -quality 92 -define jpeg:fancy-upsampling=off -define png:compression-filter=5 -define png:compression-level=9 -define png:compression-strategy=1 -define png:exclude-chunk=all -interlace none -colorspace sRGB -strip $this->fileDst");  
    //         // system("mv ".$fileTmp." ".$this->fileDst);

    //         rename($fileTmp, $this->fileDst);
    //         $product = Product::findOne($idC);
    //         if ($idImage == 0){
    //             $product->isImageMain = 1;
    //         }else{
    //             $product->isImageMore = 1;                           
    //         }
    //         if ($product->save()){
    //             return 'ok';
    //         }
    //     }
    //     //unlink($fileTmp);
    //     return 'error';
    // }



    public function actionUploadmain($id){   
        if (!empty($_FILES)){
            $this->uploadPhoto($id, $_FILES['file'], 0);           
        }
    }


   public function uploadPhoto($id, $file, $numPhoto){
        $cat = Category::findOne($id);    
        $this->dirImage     = \Yii::$app->params['dirImage'];
        $this->photoCheckDir($id);       
        $tempFile = $file['tmp_name'];
        $targetFile = $this->dirImage.'c'.$id.'/';         
        $targetFile .= $numPhoto;
        $targetFile .= ".jpg";
        move_uploaded_file($tempFile, $targetFile);        
        if ($file['size'] > 250000){
            exec("convert $targetFile -pointsize 128  -thumbnail 1024  -fill white -filter Triangle -define filter:support=2  -unsharp 0.25x0.25+8+0.065 -dither  None -posterize 136 -quality 92 -define jpeg:fancy-upsampling=off -define png:compression-filter=5 -define png:compression-level=9 -define png:compression-strategy=1 -define png:exclude-chunk=all -interlace none -colorspace sRGB -strip ".$targetFile.'_s');   
            rename($targetFile.'_s', $targetFile);                     
        }
        $cat->isImage = 1;
        if (!$cat->save()){
            Yii::info(print_r($cat->getErrors()), 'info');
        }
    }





    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        print_r(Yii::$app->request->post(), true);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Category model.
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
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
