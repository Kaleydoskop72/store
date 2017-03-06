<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\ProductColor;
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


    function photoDataSet($idP, $idC){
        $idP = ($idP == NULL) ? '-' : $idP;
        $idC = ($idC == NULL) ? '-' : $idC;        
        $fp = fopen($this->filePhotoData, 'w');
        fwrite($fp, $idP.";".$idC);
        fclose($fp);
    }
  

    function photoDataGet(&$idP, &$idC){
        $idP = 0;
        $idC = 0;
        $handle = fopen($this->filePhotoData, "r");
        $str = fread($handle, filesize($this->filePhotoData));
        fclose($handle);        
        preg_match("/([-\d]+);([-\d]+)/", $str, $matches); 
        $idP = $matches[1];
        $idC = $matches[2];
        $idP = ($idP == '-') ? NULL : $idP;
        $idC = ($idC == '-') ? NULL : $idC;         
    }


    function photoCheckDir($idP){
        $path = $this->dirImage."/p".$idP;        
        if (!file_exists($path)){
            mkdir($path);
        }
    }    


    public function beforeAction($action)    {
        if ($action->id === 'photo') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    } 


    public function actionPhoto($mode, $id, $filename){     
        $this->dirImage     = "/home/z/www/mngr-pro.oc.ru/html/web/images";
        $this->filePhotoData = $this->dirImage."/photoData.txt";
        $fileTmp = $this->dirImage."/tmp.jpg";       
        switch ($mode) {
            case MODE_CLEAR_ID:
                $this->photoDataSet(NULL, NULL);
                break;
            case MODE_SET_PRODUCT_ID:
                $this->photoDataSet($id, NULL);
                break;          
            case MODE_SET_COLOR_ID:
                $this->photoDataSet(NULL, $id);
                break; 
            default:         
                $this->photoDataGet($idP, $idC);  
                // return "---".$idP."///".$idC."+++"; 
                if ($idP == NULL){
                    $productColor = productColor::findOne($idC);
                    $idP = $productColor->idProduct;                      
                }            
                $this->photoCheckDir($idP);
                //return "---".$idP."///".$idC."+++"; 

                $this->fileDst    = $this->dirImage."/p".$idP."/";
                $dataFile = file_get_contents('php://input');
                if (!(file_put_contents($fileTmp ,$dataFile) === FALSE)){   
                    $filePrefix = ($idC != NULL) ? 'c'.$idC.'_' : '';
                    $fileName = ($mode == MODE_MAKE_PHOTO_MAIN) ? "0.jpg" : "1.jpg";
                    $this->fileDst = $this->fileDst.$filePrefix.$fileName;
                    //exec("convert $fileTmp  $this->fileDst");       
                    //             
                    // exec("convert $fileTmp -pointsize 128  -thumbnail 1024  -fill white -filter Triangle -define filter:support=2  -unsharp 0.25x0.25+8+0.065 -dither  None -posterize 136 -quality 92 -define jpeg:fancy-upsampling=off -define png:compression-filter=5 -define png:compression-level=9 -define png:compression-strategy=1 -define png:exclude-chunk=all -interlace none -colorspace sRGB -strip $this->fileDst");  
                    // system("mv ".$fileTmp." ".$this->fileDst);
                    rename($fileTmp, $this->fileDst);
                    if ($idC == NULL){
                        $product = Product::findOne($idP);
                        if ($mode == MODE_MAKE_PHOTO_MAIN){
                            $product->isImageMain = 1;
                        }else{
                            $product->isImageMore = 1;                           
                        }
                        $product->save();
                    }else{
                        $productColor = productColor::findOne($idC);
                        if ($mode == MODE_MAKE_PHOTO_MAIN){
                            $productColor->isImageMain = 1;
                        }else{
                            $productColor->isImageMore = 1;                           
                        }
                        $productColor->save();                       
                    }
                }
                //unlink($fileTmp);
        }
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);            
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
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
