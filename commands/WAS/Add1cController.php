<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;
use yii\console\Controller;
use app\models\Product;

            // 1961, 19240, 19242, 15455, 19237, 19241, 3634, 1696, 19243, 18386, 19238, 3512, 16189, 19239,
            // 8575, 15012, 12035, 7660, 14431, 14401, 10008, 14940, 9313, 17397, 12797, 15679, 15834, 4780,
            // 15027, 7157, 7059, 13942, 8643, 8226, 10759, 15082, 2326, 8430, 15430, 8444, 7059, 7057, 10116, 10925, 11181,
            // 1094, 1702, 8049, 12029, 14408, 7373, 8616, 7821, 6921, 
            // 12714, 7334, 7135, 12157,
            // 2348, 8411, 14973, 18257, 10028, 10178,
            // 16560, 7788, 12504,
            // 6779, 16869, 16192, 11089, 14940, 10524



class Add1cController extends Controller{

    public function actionIndex(){	
        $aId = [
            6602, 16194,7166, 10587, 12385, 7863, 4313, 14581, 11418, 10475, 15108, 11254, 10085, 8537, 14409, 
            9978, 8552, 14389, 5128, 2310, 537, 8301, 8338, 8087, 10452, 10532, 14607, 3112, 10533, 7129
        ];
    	foreach ($aId as $id){
    		echo "id == ".$id."\n";
    		$product = new Product();
            $product->id1C = $id;
            $product->name = '...';
            $product->idCategory = 19222;  
            $product->idManufacturer = 6; 
            $product->idParent = 0;   
            $product->idLocate = 0;    
            $product->price = 0;   
            $product->dttm_create     = date("Y-m-d H:i:s");
            $product->dttm_modify     = date("Y-m-d H:i:s");             
    		$product->save();   		 			
    	}
    }

}
