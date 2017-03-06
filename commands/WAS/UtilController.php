<?php

namespace app\commands;
use yii\console\Controller;
use app\models\Product;
use app\models\ProductStore;
use app\models\Store;
use app\models\Category;


class UtilController extends Controller{

    /**
     * переносит для пряжи главную фотографию в дополнительную
     */
    public function doMvYarnImage(){
        $dirImage = \Yii::$app->params['dirImage'];
        foreach (Category::find()->where(['typeProduct' => Category::TYPE_YARN])->all() as $cat){
            foreach (Product::find()->where(['idCategory' => $cat->id])->all() as $product){
                if ($product->idParent != 0){
                    if ($product->isImageMain){
                        echo $product->name."\n";
                        $product->isImageMain = 0;
                        $product->isImageMore = 1;
                        $product->save();
                        $dirName = $dirImage.'p'.$product->id; 
                        $cmd = 'mv '.$dirName.'/0.jpg '.$dirName.'/1.jpg';
                        echo $cmd."\n";
                        system($cmd);
                    }                  
                }
            }
        }
    }


    public function cleanStore(){
        foreach (ProductStore::find()->all() as $productStore){
            if ($store = Store::findOne($productStore->idStore)){
                if (!$store->isActive){
                    $productStore->delete();
                }
            }
        }
        foreach (Product::find()->all() as $product){
            if ($product->getTypeProduct() == Category::TYPE_KIT){
                if (!ProductStore::find()->where(['idProduct1C' => $product->id1C])->one()){
                    echo "del ".$product->name."\n";
                    $product->delete();
                }                
            }
        }
    }



    public function actionIndex($util){
        switch ($util){
            case 'mvYarnImage': $this->doMvYarnImage(); break;
            case 'findReady': Product::findReady(); break;  
            case 'cleanStore': $this->cleanStore(); break;                       
        }

    }

}
