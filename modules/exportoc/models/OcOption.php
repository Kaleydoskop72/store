<?php

namespace app\modules\exportoc\models;

use Yii;
use app\modules\exportoc\models\OcOptionDescription;
use app\modules\exportoc\models\OcOptionValueDescription;
use app\helpers\Filesystem;

class OcOption extends OcModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_option';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dboc');
    }


    public function rules()
    {
        return [
            [['type', 'sort_order'], 'required'],
            [['sort_order'], 'integer'],
            [['type'], 'string', 'max' => 32],
        ];
    }


    public function clean(){
        \Yii::$app->dboc->createCommand()->truncateTable('oc_option')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_option_description')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_option_value')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_option_value_description')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_option')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_option_value')->execute();                
    }


    public function cleanId($productId){
        foreach (OcProductOption::find()->where(['product_id' => $productId])->all() as $po){
            self::delFromTable('OcOption', 'option_id', $po->option_id);
            self::delFromTable('OcOptionDescription', 'option_id', $po->option_id);
            self::delFromTable('OcOptionValue', 'option_id', $po->option_id);
            self::delFromTable('OcOptionValueDescription', 'option_id', $po->option_id);
        }
        self::delFromTable('OcProductOption', 'product_id', $productId);
        self::delFromTable('OcProductOptionValue', 'product_id', $productId);
    }


    public static function addProduct($productName){
        $op = new OcOption();
        $op->type = 'image';
        $op->sort_order = 0;
        $op->save();

        $opD = new OcOptionDescription();
        $opD->option_id = $op->option_id;
        $opD->language_id = 1;
        $opD->name = $productName;
        $opD->save();
        return $op->option_id;
    }


    public static function addProductColor($productMain, $product, $option_id){
        $po = OcProductOption::find()->where(['product_id' => $productMain->id, 'option_id' => $option_id])->one();
        if (empty($po)){
            $po = new OcProductOption();
            $po->product_id = $productMain->id;
            $po->option_id = $option_id;
            $po->required = 0;
            if (!$po->save()){
                echo "not save OcProductOption\n";
                print_r($po->getErrors());
            }                        
        }

        $opV = new OcOptionValue();
        $opV->option_id = $option_id;
        $opV->image = '1';
        $opV->sort_order = $product->colorCode;
        if (!$opV->save()){
            echo "not save OcOptionValue\n";
            print_r($opV->getErrors());
        } 
        if ($product->isImageMain){
            $dirSrc = $product->getPathImage();
            $dirDst = self::getPathImage($product->id);
            self::photoCheckDir($dirDst);
            self::copyImage($dirSrc."/0.jpg", $dirDst."/op_".$opV->option_value_id.".jpg", TRUE);
            // $cmd = "cp ".$dirSrc."/0.jpg ".$dirDst."/op_".$opV->option_value_id.".jpg";
            // system($cmd);
            $opV->image = self::getPathImageSite($product->id)."/op_".$opV->option_value_id.".jpg";
            $opV->save();
        }else{
            if ($product->isImageMore){
                $dirSrc = $product->getPathImage();
                $dirDst = self::getPathImage($product->id);
                self::photoCheckDir($dirDst);
                self::copyImage($dirSrc."/1.jpg ", $dirDst."/op_".$opV->option_value_id.".jpg", TRUE);
                // $cmd = "cp ".$dirSrc."/1.jpg ".$dirDst."/op_".$opV->option_value_id.".jpg";
                // system($cmd);
                $opV->image = self::getPathImageSite($product->id)."/op_".$opV->option_value_id.".jpg";
                $opV->save();
            }
        }

        $opVD = new OcOptionValueDescription();
        $opVD->option_value_id = $opV->option_value_id;
        $opVD->option_id = $option_id;
        $opVD->language_id = 1;        
        $opVD->name = $product->colorName." (".$product->colorCode.")";
        if (!$opVD->save()){
            echo "not save OcOptionValueDescription\n";
            print_r($opVD->getErrors());
        }          

        $poV = new OcProductOptionValue();
        $poV->product_option_id = $po->product_option_id;
        $poV->product_id = $productMain->id;
        $poV->option_id = $option_id;      
        $poV->option_value_id = $opV->option_value_id;
        if (!$poV->save()){
            print_r($poV);
            echo "not save OcProductOptionValue\n";
            print_r($poV->getErrors());
        }            
    }


    public function getPathImage($id){
        return \Yii::$app->params['ocDirImage']."/p".$id;
    }

    
    public function getPathImageSite($id){
        return "data/product/p".$id;
        // return "catalog/p".$id;
    }


    public function photoCheckDir($path){   
        Filesystem::chkDir($path);     
        // if (!file_exists($path)){
        //     mkdir($path);
        // }
    }   

}
