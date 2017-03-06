<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\exportoc\commands;

use yii\console\Controller;
use app\models\Product;
use app\models\ProductDescription;

use app\modules\exportoc\models\OcCategory;
use app\modules\exportoc\models\OcCategoryDescription;
use app\modules\exportoc\models\OcCategoryFilter;
use app\modules\exportoc\models\OcCategoryPath;
use app\modules\exportoc\models\OcCategoryToStore;
use app\modules\exportoc\models\OcCategoryToLayout;

use app\modules\exportoc\models\OcAttribute;
use app\modules\exportoc\models\OcOption;
use app\modules\exportoc\models\OcProduct;
use app\modules\exportoc\models\OcFilter;
use app\modules\exportoc\models\OcStorage;
use app\modules\exportoc\models\OcProductStorage;
use app\modules\exportoc\models\OcManufacturer;
use app\modules\exportoc\models\OcUrlAlias;

use app\models\ProductStore;
use app\models\Category;
use app\models\Settings;

class CmdController extends Controller{

    public function doExport2IM($mode, $idCategory=0){
        OcUrlAlias::clean();
        OcUrlAlias::makeArticleUrl();
        OcManufacturer::fill($mode);
        OcFilter::fill($mode);
        OcCategory::fill($mode);
        OcAttribute::fill($mode);
        OcStorage::fill($mode);        
        // Product::updateAll(['isPublished' => 0]);
        $settings = Settings::findOne(1);
        $settings->isExport2IM = 0;
        $settings->save();
    }


    public function exportProduct(){
        OcProduct::fill();
    }


    public function doExport2IMFast($idCategory){
        OcProduct::fill($mode, $idCategory);
    }


    public function doClean(){
        OcFilter::clean();
        OcCategory::clean();
        OcAttribute::clean();
        OcOption::clean();
        OcStorage::clean();
        OcProduct::clean();
        $settings = Settings::findOne(1);
        $settings->isCleanIM = 0;
        $settings->save();
    }


    public function doTest(){
        $store = [
            'price' => 0,
            'kol'   => 0,
        ];
        foreach (($aaa = ProductStore::getPrices(1775)) as $a) {
            if ($a['price'] > 0){
                echo $a['price']."\n";
                $store = [ 
                    'price' => $a['price'],
                    'kol'   => $a['kol'],
                ];    
                break;            
            }
        }
        print_r($store);
    }


    public function actionIndex($mode, $idCategory=0){
    	switch ($mode){
            case 'web':
                if ($settings = Settings::findOne(1)){
                    if ($settings->isCleanIM){
                        $this->doClean();
                    }
                    if ($settings->isExport2IM){
                        $this->doExport2IM();
                    }
                }
                break;
    		case 'clean':
                $this->doClean();
    			break;
    		case 'exportSimple':
                echo "mode == exportSimple\n";
  			  	$this->doExport2IM("exportSimple");
    			break;
            case 'exportProduct':
                echo "mode == exportProduct\n";
                $this->exportProduct();
                break;                
            case 'exportFast':
                $this->doExport2IMFast($idCategory);
                break;
            case 'findOffStart':
                OcProduct::findOff(true);
                break;                
            case 'findOff':
                OcProduct::findOff();
                break;
            case 'test':
                $this->doTest();
                break;
    	}
    }

}


