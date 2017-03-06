<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;
use yii\console\Controller;
use app\models\Product;
use app\models\Category;


class AddCategoryController extends Controller{

    public function skipId($id){
        $aSkipId = [ 7566, 2852, 16783, 17142, 15694, 13196, 1667, 1055 ];
        foreach ($aSkipId as $sId) {
            if ($sId == $id){
                return true;
            }
        }
        return false;
    }


    public function getTypeProduct($id){
        $aId = [
            [ 'id' => 16126, 'type' => 3 ],
            [ 'id' => 16127, 'type' => 2 ],
            [ 'id' => 16128, 'type' => 1 ],
            [ 'id' => 16126, 'type' => 3 ],
            [ 'id' => 19219, 'type' => 3 ],
            [ 'id' => 490,   'type' => 3 ],                                                
        ];
        foreach ($aId as $a) {
            if ($a['id'] == $id){
                return $a['type']; 
            }
        }
        return 0; 
    }


// 16129/6486/5090;швейная галантерея/Бисер, бусины/бисер

    public function actionIndex(){
        $cat = [];
        $f = fopen("db/test.txt", "r");
        if ($f) {
            while (($str = fgets($f)) !== false) {
                $str = iconv("CP1251", "UTF-8", $str);
                echo $str;
                if (preg_match("/###/", $str)){
                    break;
                }
                $a = split(';', $str);
                $aCat = split('/', $a[0]);
                $aName = split('/', $a[1]);

                $c = $cat;
                for ($i=0; $i < count($aCat); $i++){
                    if ($i == count($aCat)-1){
                        array_push($c, [ 'id' => $aCat[$i], 'name' => $aName[$i], 'childs' => []]);
                    }else{
                        foreach ($c as $cItem) {
                            if ($cItem['id'] == ){

                            }
                        }
                    }
                }
                if (count($aCat) == 1){
                    array_push($cat, [ 'id' => $aCat[0], 'name' => $aName[0], 'childs' => []]);
                }else{
                    for ($i=0; $i < count($aCat); $i++){
                        $c = $cat[$aCat]
                    }
                    //     foreach ($cat as &$c) {
                    //         if ($c['id'] == $a[15]){
                    //             //echo "id == ".$c['id']." / ".$a[0]." / ".$a[2]."\n";
                    //             array_push($c['childs'], [ 'id' => $a[0], 'name' => $a[2]]);
                    //         }
                    //     }
                    // }
                }
            }
            fclose($f);
        }
        print_r($cat);
        return;


        foreach ($cat as $c){
            if ($this->skipId($c['id'])){
                continue;
            }
            echo $c['id']." ".$c['name']."\n";
            $modelCatP = new Category();
            $modelCatP->id = $c['id'];
            $modelCatP->idParent = 0;
            $modelCatP->name = $c['name'];
            $modelCatP->description = '';
            $modelCatP->isImage = 0;
            $modelCatP->typeProduct = $this->getTypeProduct($c['id']);
            $modelCatP->save();

            if (count($c['childs']) > 0){
                foreach ($c['childs'] as $child) {
                    if ($this->skipId($child['id'])){
                        continue;
                    }
                    echo "   ".$child['id']." ".$child['name']."\n";
                    $modelCat = new Category();
                    $modelCat->id = $child['id'];
                    $modelCat->idParent = $modelCatP->id;
                    $modelCat->name = $child['name'];
                    $modelCat->description = '';
                    $modelCat->isImage = 0;
                    $modelCat->typeProduct = $this->getTypeProduct($modelCatP->id);   
                    $modelCat->save();                 
                }
            }
        }
    }

}


     //    fopen
        // foreach ($aId as $id){
        //  echo "id == ".$id."\n";
        //  $product = new Product();
     //        $product->id1C = $id;
     //        $product->name = '...';
     //        $product->idCategory = 19222;  
     //        $product->idManufacturer = 6; 
     //        $product->idParent = 0;   
     //        $product->idLocate = 0;    
     //        $product->price = 0;   
     //        $product->dttm_create = date("Y-m-d H:i:s");
     //        $product->dttm_modify = date("Y-m-d H:i:s");             
        //  $product->save();                       
        // }

