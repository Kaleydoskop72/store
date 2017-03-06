<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\Color;
use app\models\Design;
use app\models\Label;
use app\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


const MODE_CLEAR_ID         = 0;
const MODE_SET_PRODUCT_ID   = 1;
const MODE_SET_COLOR_ID     = 2;
const MODE_MAKE_PHOTO_MAIN  = 3;
const MODE_MAKE_PHOTO_MORE  = 4;


/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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



    function photoDataSet($idP, $idC, $idImage){
        $idP = ($idP == -1) ? '-' : $idP;
        $idC = ($idC == -1) ? '-' : $idC;        
        $idImage = ($idImage == -1) ? '-' : $idImage;        
        $fp = fopen($this->filePhotoData, 'w');
        fwrite($fp, $idP.";".$idC.";".$idImage);
        fclose($fp);
    }
  

    function photoDataGet(&$idP, &$idC, &$idImage){
        $idP = 0;
        $idC = 0;
        $idImage = 0;
        $handle = fopen($this->filePhotoData, "r");
        $str = fread($handle, filesize($this->filePhotoData));
        fclose($handle);        
        preg_match("/([-\d]+);([-\d]+);([-\d]+)/", $str, $matches); 
        $idP = $matches[1];
        $idC = $matches[2];        
        $idImage = $matches[3];
        $idP = ($idP == '-') ? NULL : $idP;
        $idC = ($idC == '-') ? NULL : $idC;        
        $idImage = ($idImage == '-') ? NULL : $idImage;         
    }


    function photoCheckDir($id){
        $path = $this->dirImage."/p".$id;        
        if (!file_exists($path)){
            mkdir($path);
        }
    }    


    public function beforeAction($action){
        $aActions = [ 'photo-do', 'uploadmain', 'uploadmore', 'uploadmore2', 'uploadmore3', 
            'update-kit-category', 'color-set', 'color-get',
            'design-set', 'design-get', 'label-set', 'label-get' ];
        foreach ($aActions as $a) {
            if ($action->id === $a){
                $this->enableCsrfValidation = false;
                break;
            }            
        }             
        return parent::beforeAction($action);
    }


    public function actionColorGet($idP){
        return Color::getListHtml($idP);        
    }


    public function actionColorSet($idP, $colors){
        $product = Product::findOne($idP);
        $product->colors = $colors;
        $product->save();
        return Color::getListHtml($idP);      
    }  


    public function actionDesignGet($idP){
        return Design::getListHtml($idP);        
    }


    public function actionDesignSet($idP, $designs){
        $product = Product::findOne($idP);
        $product->designs = $designs;
        $product->save();
        return Design::getListHtml($idP);      
    }      


    public function actionLabelGet($idP){
        return Label::getListHtml($idP);        
    }


    public function actionLabelSet($idP, $label){
        $product = Product::findOne($idP);
        $product->label = $label;
        $product->save();
        return Label::getListHtml($idP);      
    }        


    public function actionPhotoSet($idP, $idC, $idImage){
        $this->dirImage      = \Yii::$app->params['dirImage'];
        $this->filePhotoData = $this->dirImage."/photoData.txt";
        $this->photoDataSet($idP, $idC, $idImage); 
        // if ($idP == -1 && $idC == -1){
        //     $this->photoDataSet(NULL, NULL, NULL);
        // }else{
        //     if ($idP != -1 && $idC == -1){
        //         $this->photoDataSet($idP, NULL, $idImage);
        //     }else{
        //         $this->photoDataSet($idP, $idC, $idImage);                
        //     }
        // }
    }  


    public function actionPhotoDel($idP, $idImage){
        if ($product = Product::findOne($idP)){
            switch ($idImage) {
                case 0: $product->isImageMain  = 0; break;
                case 1: $product->isImageMore  = 0; break;       
                case 2: $product->isImageMore2 = 0; break;
                case 3: $product->isImageMore3 = 0; break;                           
            }
            $product->save();
        }
        $filename = \Yii::$app->params['dirImage'].'p'.$product->id.'/'.$idImage.'.jpg'; 
        unlink($filename);
    }  


    public function actionPhotoDo(){     
        $this->dirImage     = \Yii::$app->params['dirImage'];
        $this->filePhotoData = $this->dirImage."/photoData.txt";
        $fileTmp = $this->dirImage."/tmp.jpg";

        $this->photoDataGet($idP, $idC, $idImage);  
        if ($idImage == NULL || $idC == NULL){
            return '';
        }
        $this->photoCheckDir($idC);
        $this->fileDst = $this->dirImage;
        $this->fileDst .= "/p".$idC."/";        
        $dataFile = file_get_contents('php://input');
        if (!(file_put_contents($fileTmp ,$dataFile) === FALSE)){   
            $fileName = $idImage.".jpg";
            $this->fileDst = $this->fileDst.$fileName;
            //exec("convert $fileTmp  $this->fileDst");
            //
            // exec("convert $fileTmp -pointsize 128  -thumbnail 1024  -fill white -filter Triangle -define filter:support=2  -unsharp 0.25x0.25+8+0.065 -dither  None -posterize 136 -quality 92 -define jpeg:fancy-upsampling=off -define png:compression-filter=5 -define png:compression-level=9 -define png:compression-strategy=1 -define png:exclude-chunk=all -interlace none -colorspace sRGB -strip $this->fileDst");  
            // system("mv ".$fileTmp." ".$this->fileDst);

            rename($fileTmp, $this->fileDst);
            $product = Product::findOne($idC);
            if ($idImage == 0){
                $product->isImageMain = 1;
            }else{
                $product->isImageMore = 1;                           
            }
            if ($product->save()){
                return 'ok';
            }
        }
        //unlink($fileTmp);
        return 'error';
    }


    public function actionUpdateKitCategory($id, $idKitCategory){
        $model = $this->findModel($id);
        $model->idKitCategory = $idKitCategory;
        $model->save();
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 0);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);        
    }



    public function actionIndex($idParent=0)
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $idParent);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndex2(){
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search2(Yii::$app->request->queryParams);

        return $this->render('index2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        $model->initNew();
        if ($model->load(Yii::$app->request->post())) {
            if (empty($model->idCategory)){
                $model->idCategory = 0;
            }
            if (empty($model->idManufacturer)){
                $model->idManufacturer = 0;
            }  
            if (empty($model->idKitCategory)){
                $model->idKitCategory = 0;
            }                       
            if (empty($model->colorCode)){
                $model->colorCode = 0;
            }             
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->isPublished = 0;
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    public function actionUploadmain($id){   
        if (!empty($_FILES)){
            $this->uploadPhoto($id, $_FILES['file'], 0);           
        }
    }


    public function actionUploadmore($id){        
        if (!empty($_FILES)){
            $this->uploadPhoto($id, $_FILES['file'], 1);           
        }
    }

    public function actionUploadmore2($id){        
        if (!empty($_FILES)){
            $this->uploadPhoto($id, $_FILES['file'], 2);           
        }
    }

    public function actionUploadmore3($id){        
        if (!empty($_FILES)){
            $this->uploadPhoto($id, $_FILES['file'], 3);           
        }
    }    

   public function uploadPhoto($id, $file, $numPhoto){
        $product = Product::findOne($id);    
        $product->isPublished = 0;
        $this->dirImage     = \Yii::$app->params['dirImage'];
        $this->photoCheckDir($id);       
        $tempFile = $file['tmp_name'];
        $targetFile = $this->dirImage.'p'.$id.'/';         
        $targetFile .= $numPhoto;
        $targetFile .= ".jpg"; 
        move_uploaded_file($tempFile, $targetFile);        
        if ($file['size'] > 250000){
            exec("convert $targetFile -pointsize 128  -thumbnail 1024  -fill white -filter Triangle -define filter:support=2  -unsharp 0.25x0.25+8+0.065 -dither  None -posterize 136 -quality 92 -define jpeg:fancy-upsampling=off -define png:compression-filter=5 -define png:compression-level=9 -define png:compression-strategy=1 -define png:exclude-chunk=all -interlace none -colorspace sRGB -strip ".$targetFile.'_s');   
            rename($targetFile.'_s', $targetFile);                     
        }
        switch ($numPhoto) {
            case 0:
                $product->isImageMain = 1; break;
            case 1:
                $product->isImageMore = 1; break;
            case 2:
                $product->isImageMore2 = 1; break;              
            case 3:
                $product->isImageMore3 = 1; break;                                
        }
        // if ($isMain){
        //     $product->isImageMain = 1;
        // }else{
        //     $product->isImageMore = 1;            
        // }
        if (!$product->save()){
            Yii::info(print_r($product->getErrors()), 'info');
        }
    }


    /**
     * Deletes an existing Product model.
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
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }




}
