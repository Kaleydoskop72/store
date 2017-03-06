<?php


namespace app\commands;
use yii\console\Controller;
use app\models\Product;
use app\models\ProductPro;
use app\models\ProductColorPro;
use app\models\Category;
use app\models\CategoryPro;
use app\models\Manufacturer;
use app\models\ManufacturerPro;
use app\models\Country;
use app\models\CountryPro;


class ProductRestoreController extends Controller{

    public function mkCopy(){
        if ($handle = opendir(\Yii::$app->params['dirImage'])){
            while ($dirname = readdir($handle)){
                if ($dirname === '.' || $dirname === '..' || $dirname === 'photoData.txt'){
                    continue;
                }
                //echo $dirname."\n";
                if ($handle2 = opendir(\Yii::$app->params['dirImage'].'/'.$dirname)){
                    while ($filename = readdir($handle2)){
                        if ($filename === '.' || $filename === '..'){
                            continue;
                        }
                        $fileSrc = \Yii::$app->params['dirImage'].'/'.$dirname.'/'.$filename;
                        $fileDst = \Yii::$app->params['dirImage'].'/'.$dirname.'/original_'.$filename;
                        $fileSize = filesize($fileSrc);
                        if ($fileSize >= 66000){
                            //echo $fileSrc." -> ".$fileDst."\n";
                            rename($fileSrc, $fileDst);
                        }
                    }
                    closedir($handle2);
                }
            }
            closedir($handle);
        }  
    }


    public function checkDir($dir){
        if (!file_exists($dir)){
            mkdir($dir);
        }
    }


    public function checkNull($val, $valNull){
        return (empty($val)) ? $valNull : $val;
    }


    public function copyImage($pSrc, $pDst, $idParent = 0){
        $dirSrc = '/home/z/www/mngr-pro0.oc.ru/html/web/images';
        if ($pDst->isImageMain){
            $dirDst = \Yii::$app->params['dirImage'].'/p'.$pDst->id;
            if ($idParent == 0){
                $cmd = 'cp '.$dirSrc.'/p'.$pSrc->id.'/0.jpg '.$dirDst.'/0.jpg';
            }else{
                $cmd = 'cp '.$dirSrc.'/p'.$pSrc->idProduct.'/c'.$pSrc->id.'_0.jpg '.$dirDst.'/0.jpg';
            }
            echo $cmd."\n"; 
            $this->checkDir($dirDst);
            system($cmd); 
        }
        if ($pDst->isImageMore){
            $dirDst = \Yii::$app->params['dirImage'].'/p'.$pDst->id;
            if ($idParent == 0){
                $cmd = 'cp '.$dirSrc.'/p'.$pSrc->id.'/1.jpg '.$dirDst.'/1.jpg';
            }else{
                $cmd = 'cp '.$dirSrc.'/p'.$pSrc->idProduct.'/c'.$pSrc->id.'_1.jpg '.$dirDst.'/1.jpg';
            }
            echo $cmd."\n"; 
            $this->checkDir($dirDst);
            system($cmd); 
        }        
    }


    public function productCopy(){
        \Yii::$app->db->createCommand()->truncateTable('product')->execute();
        // foreach (Product::find()->all() as $a) {            
        //     $a->delete();       
        // }                  
        $listProduct = ProductPro::find()->all();
        foreach ($listProduct as $pSrc){
            echo $pSrc->name."\n"; 
            $pDst = new Product();
            $pDst->idParent = 0;            
            $pDst->name = $pSrc->name;
            $pDst->description = $pSrc->description; 
            $pDst->comment = $pSrc->comment;
            $pDst->sku = $pSrc->sku;
            $pDst->colorCode = $this->checkNull($pSrc->colorCode, 0);
            $pDst->colorName = $pSrc->colorName;
            $pDst->isImageMain = $pSrc->isImageMain;
            $pDst->isImageMore = $pSrc->isImageMore;
            $pDst->isReady = 0;
            $pDst->isPublished = 0;            
            $pDst->price = $pSrc->price;
            $pDst->id1C = $pSrc->id1C;
            $pDst->idManufacturer = $pSrc->idManufacturer;
            $pDst->idCategory = $pSrc->idCategory;
            $pDst->season = $pSrc->season;
            $pDst->purpose = $pSrc->purpose;
            $pDst->weight = $this->checkNull($pSrc->weight, 0);
            $pDst->length = $this->checkNull($pSrc->length, 0);
            if (!$pDst->save()){
                echo "not save Product 1\n";
                print_r($pDst->getErrors());
            }            
            $this->copyImage($pSrc, $pDst);
            $idParentSrc = $pSrc->id;
            $idParent = $pDst->id;
            $listProductColor = ProductColorPro::find()->where(['idProduct' => $idParentSrc])->all();
            foreach ($listProductColor as $pColorSrc){
                echo $pColorSrc->colorCode."\n";                 
                $pDst = new Product();
                $pDst->idParent = $idParent;                 
                $pDst->name = $pSrc->name;
                $pDst->description = $pSrc->description; 
                $pDst->comment = $pColorSrc->comment;
                $pDst->sku = $pColorSrc->sku;
                $pDst->colorCode = $this->checkNull($pColorSrc->colorCode, 0);
                $pDst->colorName = $pColorSrc->colorName;
                $pDst->isImageMain = $pColorSrc->isImageMain;
                $pDst->isImageMore = $pColorSrc->isImageMore;
                $pDst->isReady = 0;
                $pDst->isPublished = 0;                 
                $pDst->price = $pColorSrc->price;
                $pDst->id1C = $pColorSrc->id1C;
                $pDst->idManufacturer = $pSrc->idManufacturer;
                $pDst->idCategory = $pSrc->idCategory;
                $pDst->season = $pSrc->season;
                $pDst->purpose = $pSrc->purpose;
                $pDst->weight = $this->checkNull($pSrc->weight, 0);
                $pDst->length = $this->checkNull($pSrc->length, 0);
                if (!$pDst->save()){
                    echo "not save Product 2\n";
                    print_r($pDst->getErrors());
                }    
                $this->copyImage($pColorSrc, $pDst, $idParentSrc);     
            }              
        }
    }


    public function manufacturerCopy(){
        \Yii::$app->db->createCommand()->truncateTable('manufacturer')->execute();        
        // foreach (Manufacturer::find()->all() as $a) {            
        //     $a->delete();       
        // }                
        $list = ManufacturerPro::find()->all();
        foreach ($list as $src){
            echo $src->name."\n";            
            $dst = new Manufacturer();
            $dst->id = $src->id; 
            $dst->name = $src->name;      
            $dst->idCountry = $src->idCountry; 
            $dst->save();            
        }                                
    }


    public function categoryCopy(){
        \Yii::$app->db->createCommand()->truncateTable('category')->execute();         
        // foreach (Category::find()->all() as $a) {            
        //     $a->delete();       
        // }           
        $list = CategoryPro::find()->all();
        foreach ($list as $src){
            echo $src->name."\n";
            $dst = new Category();
            $dst->id = $src->id; 
            $dst->idParent = $src->idParent;             
            $dst->name = $src->name;     
            $dst->description = $src->description;                
            $dst->isImage = $src->isImage; 
            $dst->typeProduct = 0;             
            $dst->save();
            if (!$dst->save()){
                echo "not save Category\n";
                print_r($dst->getErrors());
            }               
        }                                
    }


    public function countryCopy(){
        \Yii::$app->db->createCommand()->truncateTable('country')->execute();         
        // foreach (Country::find()->all() as $a) {            
        //     $a->delete();       
        // }                
        $list = CountryPro::find()->all();
        foreach ($list as $src){
            echo $src->name."\n";            
            $dst = new Country();
            $dst->id = $src->id; 
            $dst->name = $src->name;      
            $dst->save();
        }                                
    }


    public function actionIndex(){ 
        $this->countryCopy();          
        $this->manufacturerCopy(); 
        $this->categoryCopy();                
        $this->productCopy();
    }

}



