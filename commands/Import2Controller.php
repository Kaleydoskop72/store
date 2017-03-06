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
use app\models\Store;
use app\models\Country;
use app\models\Manufacturer;
use app\models\ProductStore;
use app\models\Settings;

class Import2Controller extends Controller{
    private $f;
    private $aCat = [];
    private $refACat;
    private $aIdWork = [];
    private $aCatParent = [];
    private $countStr = 0;

    public function importStore(){
        $this->importSpr("app\models\Store");  
    }

    public function importCountry(){
        $this->importSpr("app\models\Country");     
    }

    public function importManufacturer(){
        $this->importSpr("app\models\Manufacturer");       
    }


    public function importSpr($Model){
        $this->aIdWork = [];
        while (($str = fgets($this->f)) !== false) {
            $str = iconv("CP1251", "UTF-8", $str);
            $str = rtrim($str);
            if (preg_match("/###/", $str)){
                break;
            }
            $a = split(';', $str);
            $id = $a[0];
            $name = $a[1];                       
            $spr = $Model::findOne($id);
            if ($spr == null){
                $spr = new $Model();
                $spr->id = $id;
            }
            $spr->name = $name;
            $spr->isWork = 1;
            array_push($this->aIdWork, $id);
            if (!$spr->save()){
                echo $Model." save()\n";
                echo print_r($spr->errors, true);
            }
        }
        foreach ($Model::find()->all() as $spr) {
            if (!in_array($spr->id, $this->aIdWork)){
                $spr->isWork = 0;
                echo $spr->id."  ".$spr->name."\n";
                if (!$spr->save()){
                    echo $Model." save()\n";
                    echo $spr->errors;
                }
            }
        }           
    }


    public function skipSection(){
        while (($str = fgets($this->f)) !== false) {
            $str = iconv("CP1251", "UTF-8", $str);
            $str = rtrim($str);
            if (preg_match("/###/", $str)){
                break;
            }
        }
    }


    public function getTypeProduct($id){
        $aId = [
            [ 'id' => 7566,  'type' => Category::TYPE_NONE ],        
            [ 'id' => 16126, 'type' => Category::TYPE_KIT ],
            [ 'id' => 16127, 'type' => Category::TYPE_YARN ],
            [ 'id' => 13196, 'type' => Category::TYPE_BAGUETTE ],   
            [ 'id' => 1667,  'type' => Category::TYPE_NONE ],                        
            [ 'id' => 16128, 'type' => Category::TYPE_FABRIC ],
            [ 'id' => 19219, 'type' => Category::TYPE_FABRIC ],
            [ 'id' => 490,   'type' => Category::TYPE_FABRIC ],   
            [ 'id' => 1055,  'type' => Category::TYPE_NONE ], 
            [ 'id' => 16129, 'type' => Category::TYPE_NONE ]
        ];
        foreach ($aId as $a) {
            if ($a['id'] == $id){
                return $a['type']; 
            }
        }
        return 0; 
    }    


    public function findCat($id){
        for ($i=0; $i < count($this->refACat); $i++){
            if ($this->refACat[$i]['id'] == $id){
                $this->refACat = &$this->refACat[$i];
                return true;
            }
        }
        return false;
    }


    public function addCat(&$cat, $id, $name){
        array_push($cat, ['id' => $id, 'name' => $name, 'childs' => []]);
    }


    public function printCat($pref, $cat){
        foreach ($cat as $c) {
            print $pref.$c['id'].'/'.$c['name']."\n";
            $this->printCat($pref.'   ', $c['childs']);
        }
    }   


    public function parseCat(){
        $this->aCat = [];
        while (($str = fgets($this->f)) !== false) {
            $str = iconv("CP1251", "UTF-8", $str);
            $str = rtrim($str);
            if (preg_match("/###/", $str)){
                break;
            }
            $a = split(';', $str);
            $id = $a[0];
            $name = $a[1];
            $aId = split('/', $id);
            $aName = split('/', $name);

            $this->refACat = &$this->aCat;
            for ($i=0; $i < count($aId)-1; $i++){
                $this->findCat($aId[$i]);
                $this->refACat = &$this->refACat['childs'];
            }
            $this->addCat($this->refACat, $aId[$i], $aName[$i]);  
        }
    }


    public function saveCat($idParent, $cat, $typeProduct){
        foreach ($cat as $c){
            $modelCat = Category::findOne($c['id']);
            if ($modelCat == null){
                $modelCat = new Category();
                $modelCat->id = $c['id'];
                $modelCat->isImage = 0;
            }
            $modelCat->name = $c['name'];
            $modelCat->idParent = $idParent;
            if ($idParent == 0){
                $typeProduct = $this->getTypeProduct($c['id']);
            }
            $modelCat->isWork = $typeProduct != Category::TYPE_NONE;
            if(empty($modelCat->isWork)){
                $modelCat->isWork = 0;
            }
            $modelCat->typeProduct = $typeProduct;
            array_push($this->aIdWork, $c['id']);
            if (!$modelCat->save()){
                echo "Category(): ".print_r($modelCat->errors, true);
            }
            // if ($typeProduct == Category::TYPE_YARN && $modelCat->isProduct){
            //     $pCat = Product::find()->where(['id1C' => $c['id']])->one();
            //     if (!$pCat){
            //         $pCat = new Product();

            //     }
            // }
            $this->saveCat($c['id'], $c['childs'], $typeProduct);
            if ($modelCat->isProduct) {
                $product = Product::find()->where(['id1C' => $modelCat->id])->one();
                if (!$product){
                    $product = new Product();
                    $product->id1C = $modelCat->id;
                    echo "*** Add product!!! ".$product->id1C." ".$modelCat->name."\n";
                }
                $product->name = $modelCat->name;
                //$product->idManufacturer = $idManufacturer;
                $product->idCategory = $modelCat->idParent;
                if (!$product->save()){
                    echo "Product() ".$product->errors;
                }
            }
        }
    }


    public function importCategory(){
        $this->aIdWork = [];
        $this->parseCat();
        // $this->printCat(' ', $this->aCat);
        $this->saveCat(0, $this->aCat, 0);
        foreach (Category::find()->all() as $cat) {
            if (!in_array($cat->id, $this->aIdWork)){
                $cat->isWork = 0;
                if (!$cat->save()){
                    echo "Category() ".$cat->errors;
                }
            }
        }
    }


    public function importProduct($params){
        static $iProduct = 0;
        $rc = false;
        $iProduct++;
        if ($cat = Category::find()->where(['id' => $params['idCategory']])->one()){
            if ($cat->isWork){
                if ($iProduct >= 100){
                    //echo $params['nameKassa']."\n";
                    $iProduct = 0;
                }
                if ($product = Product::find()->where(['id1C' => $params['idProduct']])->one()){
                 
                }else{
                    $product = new Product();
                }
                $product->name = $params['nameKassa'];
                $product->nameFull = $params['nameFull'];
                $product->id1C = $params['idProduct'];
                $product->idManufacturer = $params['idManufacturer'];
                $product->idCategory = $params['idCategory'];                    
                $product->idCountry = $params['idCountry']; 
                foreach ($params['aProp'] as $key => $value) {
                    switch ($key){
                        case 'цвет':    $product->colorName = $value; break;
                        case 'состав':  $product->content = $value;   break;
                        case 'Цвет':    $product->colorName = $value; break;
                        case 'Состав':  $product->content = $value;   break;
                    }
                }
                if (!$product->save()){
                    echo "Product: ".$product->errors;
                }else{
                    $rc = true;
                }                 
            }
        }
        return $rc;
    }


    public function importProductStore($params){
        if ($s = Store::findOne($params['idStore'])){
            if ($s->isActive){
                $store = ProductStore::find()->where(['idProduct1C' => $params['idProduct'], 'idStore' => $params['idStore']])->one();
                if ($store == null){
                    $store = new ProductStore();
                    $store->idStore = $params['idStore'];
                    $store->idProduct1C = $params['idProduct'];
                }
                $store->kol = $params['kol'];
                $store->price = $params['summa'];
                if (!$store->save()){
                    echo "ProductStore() no save!\n";
                    print_r($params);
                    echo print_r($store->errors);
                }  
            }
        } 
    }


    /**
     * Links a product color.
     *
     * @param      <type>   $params  The parameters
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function linkProductColor(&$params){
        $settings = [];
        Settings::read($settings);
        $rc = false;
        // Пряжа "ALIZE Кашмира" 141 королевский синий
        if (preg_match('/\".+\"\s+(\d+)\s+(.+)/', $params['nameFull'], $matches)){
            $colorCode = 0;
            $colorName = '';
            if (count($matches) == 3){
                $colorCode = $matches[1];
                $colorName = $matches[2];
            }
            if ($cat = Category::findOne($params['idCategory'])){
                if ($cat->isProduct){
                    echo "111: found category == ".$cat->name."\n";
                    if ($productParent = Product::findOne($cat->idProduct)){
                        echo "333: found productParent == ".$productParent->name." colorCode == ".$colorCode."\n";
                        //if (!($product = Product::find()->where(['colorCode' => $colorCode, 'idParent' => $productParent->id])->one())){
                        if (!($product = Product::find()->where(['id1C' => $params['idProduct'] ])->one())){
                            unset($product);
                            $product = new Product();
                            $product->initNew();
                            echo "555 add product \n";
                            echo $params['nameFull']."\n";
                        }
                        $product->idParent = $productParent->id;
                        $product->id1C = $params['idProduct'];
                        $product->name = $params['nameKassa'];
                        $product->nameFull = $params['nameFull'];
                        $product->colorCode = $colorCode;
                        $product->colorName = $colorName;
                        $product->idManufacturer = $params['idManufacturer'];
                        if (empty($product->idManufacturer)){
                            $product->idManufacturer = $settings['idManufacturerEmpty'];
                        }
                        $product->idCategory = $cat->idParent;
                        $product->idCountry = $params['idCountry'];
                        foreach ($params['aProp'] as $key => $value){
                            switch ($key){
                                case 'цвет':    $product->colorName = $value; break;
                                case 'состав':  $product->content = $value; break;
                            }
                        }
                        // print_r($product);
                        if (!$product->save()){
                            echo "Product: ".print_r($product->errors);
                        }
                        $rc = true;
                    }   
                }
            }
        }
        return $rc;
    }


    public function linkProductColorStore($params){
        if ($s = Store::findOne($params['idStore'])){
            if ($s->isActive){
                // print_r($params);
                $store = ProductStore::find()->where(['idProduct1C' => $params['idProduct'], 'idStore' => $params['idStore']])->one();
                if ($store == null){
                    $store = new ProductStore();
                    $store->idStore = $params['idStore'];
                    $store->idProduct1C = $params['idProduct'];
                }
                $store->kol = $params['kol'];
                $store->price = ($params['kol'] > 0) ? $params['summa'] : 0;
                if (!$store->save()){                    
                    echo "Store(): ".print_r($store->errors);
                }  
            }
        } 
    }


    public function inCategory($idCat, $idParent){
        if ($idParent == 0){
            return true;
        }
        if (count($aCatParent) == 0){
            $aCat = [ $idParent ];
            foreach (Category::find()->where(['idParent' => $idParent])->all() as $cat){
                array_push($aCat, $cat->id);
                foreach (Category::find()->where(['idParent' => $cat->idParent])->all() as $cat2){
                    array_push($aCat, $cat2->id);
                }
            }            
        }
        return in_array($idCat, $aCat);
    }


    public function parseProduct($handleProduct, $handleProductStore, $idCategory=0){
        $this->aCat = [];
        while (true){
            $posOld = ftell($this->f);
            if (($str = fgets($this->f)) == false){
                echo "okEnd\n";
                break;
            }
            $this->countStr++;
            $str = iconv("CP1251", "UTF-8", $str);
            $str = rtrim($str);
            if (preg_match("/###/", $str)){
                echo "okEnd\n";
                break;
            }
            $a = split(';', $str);
            if ($this->countStr > 2000 && count($a) == 11){
                fseek($this->f, $posOld);
                echo "ok\n";
                break;
            }
            if (count($a) == 11){
                $params['idFull']         = $a[0];
                $params['nameFull']       = $a[1];
                $params['nameKassa']      = $a[2];                
                $params['idManufacturer'] = $a[3];
                $params['nameManufacturer'] = $a[4];
                $params['idCountry']      = $a[5];
                $params['nameCountry']    = $a[6];
                $params['ed']             = $a[7];
                $params['section']        = $a[8];
                $params['property']       = $a[9];
                $a = split('/', $params['idFull']);
                if (count($a) < 2) continue;
                $params['idProduct']  = $a[count($a)-1];
                $params['idCategory'] = $a[count($a)-2];
                if ($this->inCategory($params['idCategory'], $idCategory)){
                    //echo "handle: ".$params['idCategory']." ".$params['nameFull']."\n";
                    $params['aProp'] = [];
                    if (strlen($params['property']) > 0){
                        //echo "property == ".$property."\n";
                        $aPropStr = split('\|', $params['property']);
                        foreach ($aPropStr as $pStr){
                            $aPropHash = split(':', $pStr);
                            $params['aProp'][$aPropHash[0]] = $aPropHash[1];
                        }
                    }
                    // echo $params['nameKassa']."\n";
                    // print_r($params);
                    $isProductFound = $this->$handleProduct($params);                    
                }else{
                    $isProductFound = false;
                }
            }else{
                if ($isProductFound){
                    //print_r($a);
                    $params['idStore']    = $a[0];
                    $params['kol']        = $a[1];                    
                    $params['summa']      = $a[2];
                    $this->$handleProductStore($params);
                }                      
            }
        }        
    }


    public function makeChkCat(){
        foreach (Category::find()->where([ 'isProduct' => 1 ])->all() as $cat){
            $isAdd = false;
            if ($cat->idProduct){
                if ($product = Product::findOne($cat->idProduct)){
                    if ($product->id1C != $cat->id){
                        echo "add productMain ".$cat->name."\n";
                        $productMain = new Product();
                        $productMain->id1C = $cat->id;
                        $productMain->name = $cat->name;
                        $productMain->idCategory = $cat->idParent;
                        if (!$productMain->save()){
                            echo "not save() productMain\n";
                            echo print_r($productMain->errors);
                        }
                        foreach (Product::find()->where(['idParent' => $product->id])->all() as $productChild){
                            echo "relink productChild ".$productChild->name."\n";
                            $productChild->idParent = $productMain->id;
                            if ($productChild->save(false)){
                                echo "not save() productChild\n";
                                echo print_r($productChild->errors);
                            }
                        }
                        echo "relink product ".$product->name."\n";
                        $product->idParent = $productMain->id;
                        $product->idCategory = $cat->idParent;
                        if (!$product->save()){
                            echo "not save() product\n";
                            echo print_r($product->errors);
                        }
                        $cat->idProduct = $productMain->id;
                        $cat->save();
                    }
                }else{
                    $isAdd = true;
                }
            }else{
                $isAdd = true;
            }
            if ($isAdd){
                echo "isAdd product ".$cat->name."\n";
                $productMain = new Product();
                $productMain->id1C = $cat->id;
                $productMain->name = $cat->name; 
                if (!$productMain->save()){
                    echo "not save() productMain\n";
                    echo print_r($productMain->errors);
                }
            }
        }
    }


    /**
     * Перебирает все категории-продукты.
     * if ($cat->idProduct){ // заполнен
     *      $product->idCategory = $cat->idParent;
     *      $product->name = $cat->name;
     *      и для всех потомков $product:
     *          $child->idCategory = $cat->idParent;
     * }else{
     *      ищем $product с $product->id1C == $cat->id
     *      $product->id1C = $cat->id;
     *      $product->idCategory = $cat->idParent;
     *      $product->name = $cat->name;
     *      $product->description = $child->description;
     *      $product->purpose = $child->purpose;
     * }
     */
    public function makeLinkCat(){                                          // , 'isReady' => 1
        foreach (Category::find()->where(['isProduct' => 1, 'isWork' => 1])->all() as $cat){
            echo "catName == ".$cat->name."\n";
            if (!empty($cat->idProduct) && $product = Product::findOne($cat->idProduct)){
                echo "product == ".$product->name." / ".$cat->name." / ".$cat->id."\n";
                $product->idCategory = $cat->idParent;
                $product->name = $cat->name;
                $cat->isReady = $product->isReady;
                if (!$cat->save()){
                    echo print_r($cat->errors);
                }
                if (!$product->save()){
                    echo print_r($product->errors);
                }
                foreach (Product::find()->where(['idParent' => $product->id])->all() as $child) {
                    echo "product == ".$product->name." / ".$cat->name."\n";
                    $child->idCategory = $cat->idParent;
                    if (!$child->save()){
                        echo print_r($child->errors);
                    }
                }
            }else{
                echo "!!!category == ".$cat->name."\n";
                if (!($product = Product::find()->where(['id1C' => $cat->id])->one())){
                    $product = new Product();
                    $product->initNew();
                    $product->save(false);
                }
                $productParentId = $product->id;
                $product->id1C = $cat->id;
                $product->idCategory = $cat->idParent;
                $product->name = $cat->name;

                // foreach (Product::find()->where('idCategory' => $cat->id)->all() as $child){

                // }

                if ($child = Product::find()->where(['idCategory' => $cat->id])->one()){
                    if (strlen(trim($child->description)) > 0){
                        $product->description = $child->description;
                    }
                    if (strlen(trim($child->purpose)) > 0){
                        $product->purpose = $child->purpose;
                    }
                }
                $product->save();
                $cat->idProduct = $product->id;
                $cat->save();
            }
        }
    }


    public function makeLinkColor($i){ 
        $this->f = fopen($this->getImportFilename($i), "r");
        if ($this->f){          
            $this->parseProduct('linkProductColor', 'linkProductColorStore', 0); 
            fclose($this->f);
        }        
    }


    /**
     * Удаляет товары типа Пряжа с id1C == 0
     *      Удаляет связи в ProductStore
     *      удаляет каталог с фото
     */
    function makeDelZero1C(){
        foreach (Product::find()->where(['id1C' => 0])->all() as $product){
            if ($product->getTypeProduct() == Category::TYPE_YARN){
                foreach (ProductStore::find()->where(['idProduct' => $product->id])->all() as $productStore){
                    echo "  delete ProductStore: ".$productStore->idProduct." ".$productStore->idStore."\n";
                    $productStore->delete();
                }
                echo "delete Product: ".$product->id."\n";
                $product->delete();
                $cmd = "rm -r ".\Yii::$app->params['dirImage']."p".$product->id;
                echo $cmd."\n";
                system($cmd);
            }
        }
    }


    public function getImportFilename(){
        $filename = \Yii::$app->params['dirImport'].'/1c_to_mngr.txt';
        return $filename;
    }


    public function importPosWrite($pos){
        if ($fPos = fopen($this->getImportFilename().".pos", "w")){
            fwrite($fPos, $pos);
            fclose($fPos);
        }        
    }


    public function importPosRead(){
        $pos = 0;
        if ($fPos = fopen($this->getImportFilename().".pos", "r")){
            $pos = fgets($fPos);
            fclose($fPos);
        }
        return (int) $pos;
    }


    public function importOpen($isStart=0){
        if ($isStart == 1){
            $this->importPosWrite(0);
        }
        if (file_exists($this->getImportFilename())){            
            if ($this->f = fopen($this->getImportFilename(), "r")){
                fseek($this->f, $this->importPosRead());
                return 1;
            }
        }
        return 0;
    }


    public function importClose(){
        $q = ftell($this->f);
        $this->importPosWrite(ftell($this->f));
        fclose($this->f);
    }


    public function importStart(){
        if ($this->importOpen(1)){
            echo "in importStart()\n";
            \Yii::$app->db->createCommand()->truncateTable('product_store')->execute();
            $this->importStore();
            $this->importCountry();
            $this->importManufacturer();
            $this->importCategory();
            $this->importClose();        
        }
    }


    public function importProductN(){
        $this->countStr = 0;
        if ($this->importOpen()){
            $this->parseProduct('importProduct', 'importProductStore', $idCategory=0);
            $this->importClose();
        }
    }

    /**
     * { Имортирует данные из файла выгрузки 1C }
     *
     * @param      <string>  $mode   режимы выгрузки:
     *      - 'import'  - полная выгрузка
     *      - 'linkCat' - 
     *      - 'linkColor'
     *      - 'delZero1C'
     */
    public function actionIndex($mode){
        $idCategory = 0;
        switch ($mode){
            case 'importStart':
                $this->importStart();
                break;
            case 'importNext':
                if (file_exists($this->getImportFilename())){
                    $this->importProductN();
                }
                break;                
            case 'linkCat':
                echo "in linkCat \n";
                $this->makeLinkCat();
                break;
            case 'chkCat':
                echo "in chkCat \n";
                $this->makeChkCat();
                break;
            case 'linkColor':
                $this->makeLinkColor();
                break;
            case 'linkColor_n':
                if (file_exists($this->getImportFilename($nImport))){            
                    $this->makeLinkColor($nImport);
                }
                break;                
            case 'delZero1C':
                $this->makeDelZero1C();
                break;
        }
    }

}
