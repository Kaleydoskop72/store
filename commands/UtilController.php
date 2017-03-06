<?php

namespace app\commands;
use yii\console\Controller;
use app\models\Product;
use app\models\BadProduct;
use app\models\ProductStore;
use app\models\Store;
use app\models\Category;
use app\models\ProductPro;
use app\models\ProductColorPro;
use app\models\Manufacturer;
use app\models\Settings;
use app\models\History;
use app\helpers\MngrLib;

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




    /**
     *  удаляет цены по неактивным складам
     *  
     */
    public function cleanStore_WAS(){
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
                }else{
                    $storeRaw = [];
                    foreach (ProductStore::find()->where(['idProduct1C' => $product->id1C])->all() as $productStore){
                        array_push($storeRaw, [
                                'idStore' => $productStore->idStore,
                                'price' => $productStore->price,
                                'kol' => $productStore->kol
                            ]);
                        $productStore->delete();
                    }
                    $store = [];
                    foreach ($storeRaw as $stRaw){
                        $isFound = false;
                        foreach ($store as $st){
                            if ($st['idStore'] == $stRaw['idStore']){
                                if ($st['price'] < $stRaw['price']){
                                    $st['price'] = $stRaw['price'];
                                }
                                $st['kol'] += $stRaw['kol'];
                                $isFound = true;
                            }
                        }
                        if (!$isFound){
                            array_push($store, [
                                    'idStore' => $stRaw['idStore'],
                                    'price' => $stRaw['price'],
                                    'kol' => $stRaw['kol']                                    
                                ]);
                        }
                    }
                    foreach ($store as $st) {
                        $productStore = new ProductStore();
                        $productStore->idProduct1C = $product->id1C;
                        $productStore->idStore = $st['idStore'];
                        $productStore->kol   = $st['kol'];
                        $productStore->price = $st['price'];
                        $productStore->save();                                                
                    }
                }                
            }
        }
    }



    public function importMebel(){
        // foreach (ProductPro::find()->all() as $product0) {
        //     if (in_array($product0->idCategory, [3, 7, 8, 9, 10])){
        //         if ($product0->isImageMain && !empty($product0->id1C)){
        //             if ($product = Product::find()->where(['id1C' => $product0->id1C])->one()){
        //                 echo "id1C == ".$product0->id1C."\n";
        //                 $product->description = $product0->description;
        //                 $product->purpose = $product0->purpose;
        //                 $product->colorName = $product0->colorName;
        //                 $product->sku = $product0->sku;
        //                 $product->structure = $product0->structure;
        //                 $dir = \Yii::$app->params['dirImage']."/p".$product->id;
        //                 if (!file_exists($dir)){
        //                     echo $dir."\n";
        //                     mkdir($dir);
        //                 }
        //                 $dir0 = '/home/z/www/WAS/mngr-pro0.oc.ru/html/web/images';
        //                 $fileSrc = $dir0."/p".$product0->id."/0.jpg";
        //                 if (file_exists($fileSrc)){
        //                     system("cp ".$fileSrc." ".$dir."/0.jpg");
        //                     $product->isImageMain = 1;                            
        //                 }
        //                 $fileSrc = $dir0."/p".$product0->id."/1.jpg";
        //                 if (file_exists($fileSrc)){
        //                     system("cp ".$fileSrc." ".$dir."/1.jpg");
        //                     $product->isImageMore = 1;                            
        //                 }                        
        //                 $product->save();                   
        //             }else{
        //                 echo "!!! no found id1C == ".$product0->id1C."\n";
        //             }
        //         }
        //     }
        // }
        foreach (ProductColorPro::find()->all() as $productColor0) {
            if ($product0 = ProductPro::findOne($productColor0->idProduct)){
                if (in_array($product0->idCategory, [3, 7, 8, 9, 10])){
                    if ($productColor0->isImageMain && !empty($productColor0->id1C)){
                        if ($product = Product::find()->where(['id1C' => $productColor0->id1C])->one()){
                            echo "id1C == ".$productColor0->id1C."\n";
                            $product->description = $product0->description;
                            $product->purpose = $product0->purpose;
                            $product->colorName = $productColor0->colorName;
                            $product->sku = $productColor0->sku;
                            $product->structure = $product0->structure;
                            $dir = \Yii::$app->params['dirImage']."/p".$product->id;
                            if (!file_exists($dir)){
                                echo $dir."\n";
                                mkdir($dir);
                            }
                            $dir0 = '/home/z/www/WAS/mngr-pro0.oc.ru/html/web/images';
                            $fileSrc = $dir0."/p".$product0->id."/c".$productColor0->id."_0.jpg";
                            if (file_exists($fileSrc)){
                                system("cp ".$fileSrc." ".$dir."/0.jpg");
                                $product->isImageMain = 1;                            
                            }
                            $fileSrc = $dir0."/p".$product0->id."/c".$productColor0->id."_1.jpg";
                            if (file_exists($fileSrc)){
                                system("cp ".$fileSrc." ".$dir."/1.jpg");
                                $product->isImageMore = 1;                            
                            }                        
                            $product->save();                   
                        }else{
                            echo "!!! no found id1C == ".$productColor0->id1C."\n";
                        }
                    }
                }                
            }

        }        
    }


    public function setCatIsReady(){
        $aCatRoot = [ 490, 16128, 19219, 16127 ];
        foreach ($aCatRoot as $catRoot) {
            echo $catRoot."\n";
            foreach (Category::find()->where(['idParent' => $catRoot])->all() as $cat1) {
                echo "  ".$cat1->name."\n";
                $cat1->isReady = 1;
                $cat1->save();
                foreach (Category::find()->where(['idParent' => $cat1->id])->all() as $cat2) {
                    echo "      ".$cat2->name."\n";
                    $cat2->isReady = 1;
                    $cat2->save();
                    foreach (Category::find()->where(['idParent' => $cat2->id])->all() as $cat3) {
                        echo "3:          ".$cat3->name."\n";
                        $cat3->isReady = 1;
                        $cat3->save();
                        foreach (Category::find()->where(['idParent' => $cat3->id])->all() as $cat4) {
                            echo "4:              ".$cat4->name."\n";
                            $cat4->isReady = 1;
                            $cat4->save();
                        }                             
                    }                    
                }
            }
        }
    }


    public function setKitCategory(){
        echo "in setKitCategory()\n";
        $idCategory = 17037;
        foreach (Product::find()->where(['idCategory' => $idCategory])->all() as $product) {
            if (true || $product->isImageMain){
                if (preg_match('/(\d+)-(\d+)/', $product->sku, $matches)){
                    print_r($matches);
                    $skuNum1 = $matches[1];
                    $skuNum2 = $matches[2];
                    switch ($skuNum1) {
                        case 0: $product->idKitCategory = 9;  break;
                        case 1: $product->idKitCategory = 4;  break;
                        case 2: $product->idKitCategory = 5;  break;
                        case 3: $product->idKitCategory = 1;  break;
                        case 4: $product->idKitCategory = 3;  break;
                        case 5: $product->idKitCategory = 12; break;
                        case 6: $product->idKitCategory = 9;  break;                        
                    }
                    $product->save();
                }else{
                    echo $product->sku." ???\n";
                }
            }
        }
    }


    // public function setKitCategory(){
    //     echo "in setKitCategory()\n";
    //     $idCategory = 3108;
    //     foreach (Product::find()->where(['idCategory' => $idCategory])->all() as $product) {
    //         if (true || $product->isImageMain){
    //             if (preg_match('/([a-zA-Zа-яёА-Я0-9]+)-(\d+)/', $product->sku, $matches)){
    //                 // print_r($matches);
    //                 $skuChar = $matches[1];
    //                 $skuNum = $matches[2];
    //                 if ($skuChar == 'В' && $skuNum >= 152 && $skuNum <= 186){
    //                     echo $product->name."\n";
    //                     $product->idKitCategory = 10;
    //                     $product->save();
    //                 }
    //             }else{
    //                 echo $product->sku." ???\n";
    //             }
    //         }
    //     }
    // }


    public function correctKroshe(){
        echo "in correctKroshe()\n";
        $idCategory = 3108;
        foreach (Product::find()->where(['idCategory' => $idCategory])->all() as $product) {
            switch ($product->idKitCategory) {
                case 1: $product->idKitCategory = 16; break;
                case 4: $product->idKitCategory = 15; break;
                case 5: $product->idKitCategory = 17; break;
            }
            $product->save();
        }
    }    


    public function checkPrice(){
        foreach (Product::find()->all() as $product) {
            $aPrice = [];
            foreach (ProductStore::find()->where(['idProduct1C' => $product->id1C])->all() as $store){
                array_push($aPrice, $store->price);
            }
            foreach ($aPrice as $p1) {
                foreach ($aPrice as $p2) {
                    if ($p2 == 0) {
                        echo "Zero! ".$product->name." / ".$product->id1C."\n";
                        print_r($aPrice);
                        break;                        
                    }
                    if (($p1/$p2) > 2){
                        echo $product->name." / ".$product->id1C."\n";
                        print_r($aPrice);
                        break;
                    }
                }
            }
        }
    }


    public function yandexHeader(){
        $cdate = date("Y-m-d H:i",time());
        $csite = "http://tkanirukodelie.ru/";
        $cname = "ТканиРукоделие";
        $csite2 = $csite;
        $cname2 = $cname;
        $cdesc = "Ткани, пряжа, наборы для вышивания";
        $yandex=<<<END
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="$cdate">

<shop>
<name>$cname</name>
<company>$cdesc</company>
<url>$csite</url>

<currencies>
    <currency id="RUR" rate="1"/>
</currencies>

END;
        return $yandex;
    }


    public function yandexCategory(){
        $yandex = "<categories>\n";
        foreach (Category::find()->where(['idParent' => 0, 'isReady' => 1, 'isWork' => 1, 'typeProduct' => Category::TYPE_KIT])->all() as $cat){
            $yandex .= '<category id="'.$cat->id.'">'.$cat->name.'</category>'."\n";
            foreach (Category::find()->where(['idParent' => $cat->id, 'isReady' => 1, 'isWork' => 1])->all() as $catChild){
                $yandex .= '<category id="'.$catChild->id.'" parentId="'.$cat->id.'">'.$catChild->name.'</category>'."\n";
            }
        }
        $yandex .= "</categories>\n";
        return $yandex;
    }


    public function yandexDeliveryCpa(){
        $yandex=<<<END
<delivery-options>
  <option cost="500" days="0"/>
  <option cost="300" days="1-3"/>
</delivery-options>
<cpa>1</cpa>

END;
        return $yandex;
    }


    public function yandexOffer($p, $c){
        $url = 'http://tkanirukodelie.ru/index.php?route=product/product&path='.$c->id.'&product_id='.$p->id;
        ProductStore::getPriceAvailble($p->id1C, $price);
        $urlImage0 = '';
        $urlImage1 = '';
        $urlImage2 = '';
        $urlImage3 = '';        
        if ($p->isImageMain){
            $urlImage0 = 'http://tkanirukodelie.ru/image/cache/data/product/p'.$p->id.'/0-1024x800.jpg';
        }
        if ($p->isImageMore){
            $urlImage1 = 'http://tkanirukodelie.ru/image/cache/data/product/p'.$p->id.'/1-1024x800.jpg';
        }
        if ($p->isImageMore2){
            $urlImage2 = 'http://tkanirukodelie.ru/image/cache/data/product/p'.$p->id.'/2-1024x800.jpg';
        }
        if ($p->isImageMore3){
            $urlImage3 = 'http://tkanirukodelie.ru/image/cache/data/product/p'.$p->id.'/3-1024x800.jpg';
        }
        $name = $p->name;
        $brand = '';
        if ($man = Manufacturer::findOne($p->idManufacturer)){
            $brand = $man->name;
        }
        $model = '';
        $description = $p->description;

        $yandex = <<<END
    <offer id="$p->id" available="true" bid="80" cbid="90">
        <url>$url</url>
        <price>$price</price>
        <currencyId>RUR</currencyId>
        <categoryId>$p->idCategory</categoryId>

END;
        $yandex .= '        <picture>'.$urlImage0."</picture>\n";
        if (strlen($urlImage1) > 0){
            $yandex .= "        <picture>".$urlImage1."</picture>\n";
        }
        if (strlen($urlImage2) > 0){
            $yandex .= "        <picture>".$urlImage2."</picture>\n";
        }
        if (strlen($urlImage3) > 0){
            $yandex .= "        <picture>".$urlImage3."</picture>\n";
        }
        $yandex .= <<<END
        <store>false</store>
        <delivery>true</delivery>
        <name>$name</name>      
        <vendor>$brand</vendor>
        <model>$model</model>
        <description>$description</description>
        <sales_notes></sales_notes>
        <age>0</age> 
        <manufacturer_warranty>false</manufacturer_warranty>

END;
        $yandex .= '        <param name="Тип">набор для вышивания</param>'."\n";
        if (strlen($p->floss)){
            $yandex .= '        <param name="Мулине">'.$p->floss.'</param>'."\n";
        }
        if (strlen($p->canva)){
            $yandex .= '        <param name="Канва">'.$p->canva.'</param>'."\n";
        }        
        if (strlen($p->colorCount)){
            $yandex .= '        <param name="Количество цветов">'.$p->colorCount.'</param>'."\n";
        }     
        if (strlen($p->needle)){
            $yandex .= '        <param name="Игла">'.$p->needle.'</param>'."\n";
        }  
        if (strlen($p->tech)){
            $yandex .= '        <param name="Техника вышивания">'.$p->tech.'</param>'."\n";
        }    
        if (strlen($p->thread)){
            $yandex .= '        <param name="Нитки">'.$p->thread.'</param>'."\n";
        }      
        if (strlen($p->sizePack)){
            $yandex .= '        <param name="Размер упаковки">'.$p->sizePack.'</param>'."\n";
        }                                               
        $yandex .= "    </offer>\n"; 
        return $yandex;
    }


    public function yandexOffers(){
        $yandex = "<offers>\n";
        foreach (Product::find()->where(['isReady' => 1])->all() as $product){
            $typeProduct = $product->getTypeProduct();
            $cat = Category::findOne($product->idCategory);
            if ($typeProduct == Category::TYPE_KIT){
                $yandex .= $this->yandexOffer($product, $cat);
            }
        }
        $yandex .= "</offers>\n";
        return $yandex;
    }


    public function yandexEnd(){
        $yandex = "</yml_catalog>\n";
        return $yandex;
    }

    
    public function export2YandexMarket(){
        $yandex = $this->yandexHeader();
        $yandex .= $this->yandexCategory();
        $yandex .= $this->yandexDeliveryCpa();
        $yandex .= $this->yandexOffers();
        $f = fopen("./yandex.yml", "w");
        //echo "yandex == ".$yandex."\n";
        fwrite($f, $yandex);
        fclose($f);
    }


    public function handleCleanStore(&$product, $fabricExpire){
        $typeProduct = $product->getTypeProduct();
        if ($typeProduct == Category::TYPE_FABRIC){
            foreach (ProductStore::find()->where(['idProduct1C' => $product->id1C])->all() as $store){
                if ($store->kol <= $fabricExpire){
                    $store->delete();
                }
            }
        }
        if ($typeProduct == Category::TYPE_YARN) {
            $a = ProductStore::getPrices($product->id1C);
            if (count($a) > 0){
                foreach (Store::find()->where(['isActive' => 1])->all() as $store){
                    $inArray = false;
                    foreach ($a as $item) {
                        if ($item['idStore'] == $store->id){
                            $price = $item['price'];
                            $inArray = true;
                            break;
                        }
                    }
                    if (!$inArray){
                        // echo "add: ".$product->name." ".$store->id." ".$price."\n";
                        if (!empty($price)){
                            $ps = new ProductStore();
                            $ps->idProduct1C = $product->id1C;                        
                            $ps->idStore   = $store->id;
                            $ps->price = $price;
                            $ps->kol = 1;
                            $ps->save();
                        }
                    }
                }
            }
        }        
    }



    public function handleCheckYarn(&$product){
        if ($product->getTypeProduct() == Category::TYPE_YARN){
            $aPriceStore = [];
            foreach (Product::find()->where(['idParent' => $product->id])->all() as $child){
                $aPriceStoreChild = ProductStore::getPriceAvailble($child->id1C, $price);
                if (count($aPriceStoreChild) > 0){
                    foreach ($aPriceStoreChild as $priceChild){
                        for ($i=0; $i < count($aPriceStore) && $priceChild['idStore'] != $aPriceStore[$i]['idStore']; $i++);
                        if ($priceChild['isActive'] && $priceChild['price'] > 0 && $priceChild['kol'] > 0){
                            $aPriceStore[$i]['idStore'] = $priceChild['idStore'];
                            $aPriceStore[$i]['price'] = $priceChild['price'];
                            if (array_key_exists('kol', $aPriceStore[$i])){
                                $aPriceStore[$i]['kol']  += $priceChild['kol'];
                            }else{
                                $aPriceStore[$i]['kol']  = $priceChild['kol'];
                            }
                            $aPriceStore[$i]['order'] = $priceChild['order'];
                            $aPriceStore[$i]['isActive'] = $priceChild['isActive'];
                        }
                    }
                }
            }
            foreach (ProductStore::find()->where(['idProduct1C' => $product->id1C])->all() as $ps){
                // echo "      ".$ps->id."\n";
                $ps->delete();
            }
            foreach ($aPriceStore as $priceStore) {
                $ps = new ProductStore();
                $ps->idProduct1C = $product->id1C;
                $ps->idStore = $priceStore['idStore'];
                $ps->price = $priceStore['price'];
                $ps->kol = $priceStore['kol'];
                $ps->save();
            }
        }        
    }


    public function handleNoPrice2Arch(&$product){
        $a = ProductStore::getPrices($product->id1C);            
        $isArchive = (count($a) == 0) ? 1 : 0;
        $product->isArchive = $isArchive;       
    }



    public function handleProducts(){
        echo "in handleProducts() \n";
        $list = Product::find()->all();        
        Settings::read($settings);
        MngrLib::printPrc($i = 0, count($list));
        foreach ($list as $product){
            MngrLib::printPrc($i++);
            if ($product->idParent == 0){
                $this->handleCleanStore($product, $settings['fabricExpire']);
                $this->handleCheckYarn($product);
            }
            $this->handleNoPrice2Arch($product);
            if ($product->getSnapShot() != $product->snapShot){
                echo "change: ".$product->id." ".$product->name."\n";
                $product->snapShot = $product->getSnapShot();
                if (!$product->save()){
                    echo "no save!\n";
                    print_r($product->errors);
                }
            }        
        } 
    }



    function cleanStore($isFirst){
        // if ($isFirst){
        //     Product::updateAll(['isHandled' => 0]);
        // }
        // $list = Product::find()->where([ 'idParent' => 0, 'isHandled' => 0])->limit(2000)->all();
        $list = Product::find()->where([ 'idParent' => 0])->all();        
        // if (count($list) == 0){
        //     echo "ok\n";
        //     return;
        // }
        Settings::read($settings);
        foreach ($list as $product){
            $typeProduct = $product->getTypeProduct();
            if ($typeProduct == Category::TYPE_FABRIC){
                foreach (ProductStore::find()->where(['idProduct1C' => $product->id1C])->all() as $store){
                    if ($store->kol <= $settings['fabricExpire']){
                        $store->delete();
                    }
                }
            }
            if ($typeProduct == Category::TYPE_YARN) {
                $a = ProductStore::getPrices($product->id1C);
                if (count($a) > 0){
                    foreach (Store::find()->where(['isActive' => 1])->all() as $store){
                        $inArray = false;
                        foreach ($a as $item) {
                            if ($item['idStore'] == $store->id){
                                $price = $item['price'];
                                $inArray = true;
                                break;
                            }
                        }
                        if (!$inArray){
                            echo "add: ".$product->name." ".$store->id." ".$price."\n";
                            $ps = new ProductStore();
                            $ps->idProduct1C = $product->id1C;                        
                            $ps->idStore   = $store->id;
                            $ps->price = $price;
                            $ps->kol = 1;
                            $ps->save();
                        }
                    }
                }
            }
            $product->isHandled = 1;
            $product->save();
        }
    }



    public function checkYarn($isFirst){
        echo "in checkYarn() \n";
        // if ($isFirst){
        //     Product::updateAll(['isHandled' => 0]);
        // }
        // $list = Product::find()->where([ 'idParent' => 0, 'isHandled' => 0])->limit(2000)->all();
        $list = Product::find()->where([ 'idParent' => 0])->all();        
        // if (count($list) == 0){
        //     echo "ok\n";
        //     return;
        // }        
        foreach ($list as $product){
            if ($product->getTypeProduct() == Category::TYPE_YARN){
                $aPriceStore = [];
                foreach (Product::find()->where(['idParent' => $product->id])->all() as $child){
                    $aPriceStoreChild = ProductStore::getPriceAvailble($child->id1C, $price);
                    if (count($aPriceStoreChild) > 0){
                        foreach ($aPriceStoreChild as $priceChild){
                            for ($i=0; $i < count($aPriceStore) && $priceChild['idStore'] != $aPriceStore[$i]['idStore']; $i++);
                            if ($priceChild['isActive'] && $priceChild['price'] > 0 && $priceChild['kol'] > 0){
                                $aPriceStore[$i]['idStore'] = $priceChild['idStore'];
                                $aPriceStore[$i]['price'] = $priceChild['price'];
                                if (array_key_exists('kol', $aPriceStore[$i])){
                                    $aPriceStore[$i]['kol']  += $priceChild['kol'];
                                }else{
                                    $aPriceStore[$i]['kol']  = $priceChild['kol'];
                                }
                                $aPriceStore[$i]['order'] = $priceChild['order'];
                                $aPriceStore[$i]['isActive'] = $priceChild['isActive'];
                            }
                        }
                    }
                }
                echo "delete for: ".$product->id1C." ".$product->id." ".$product->idParent." ".$product->name."\n";
                foreach (ProductStore::find()->where(['idProduct1C' => $product->id1C])->all() as $ps){
                    echo "      ".$ps->id."\n";
                    $ps->delete();
                }
                foreach ($aPriceStore as $priceStore) {
                    $ps = new ProductStore();
                    $ps->idProduct1C = $product->id1C;
                    $ps->idStore = $priceStore['idStore'];
                    $ps->price = $priceStore['price'];
                    $ps->kol = $priceStore['kol'];
                    $ps->save();
                }
            }
            $product->isHandled = 1;
            $product->save();
        }
    }




    public function noPrice2Arch($isFirst){
        echo "in noPrice2Arch() \n";
        // if ($isFirst){
        //     Product::updateAll(['isHandled' => 0]);
        // }
        // $list = Product::find()->where([ 'isHandled' => 0])->limit(2000)->all();
        $list = Product::find()->all();
        // if (count($list) == 0){
        //     echo "ok\n";
        //     return;
        // }        
        $i = 0;
        foreach ($list as $product){
            if ($i == 0){
                echo $product->name."\n";
            }
            $i++;
            if ($i > 100){
                $i = 0;
            }
            $a = ProductStore::getPrices($product->id1C);            
            $isArchive = (count($a) == 0) ? 1 : 0;
            $product->isArchive = $isArchive;
            $product->isHandled = 1;
            if (!$product->save()){
                echo "no save!\n";
                print_r($product->errors);
            }
        }
    }


    public function findLost(){
        foreach (Product::find()->all() as $product) {
            $typeProduct = $product->getTypeProduct();
            if ($typeProduct == Category::TYPE_YARN && $product->idParent != 0){
                if (!Product::findOne($product->idParent)){
                    echo "!!! ".$product->id." ".$product->isImageMain." ".$product->isImageMore." ".$product->name."\n";
                }
            }
        }
        $predId = 0;
        foreach (Product::find()->orderBy(['id1C'=>SORT_ASC])->all() as $product){
            if ($product->id1C != 0){
                if ($predId == $product->id1C){
                    // echo $product->id1C." ".$product->name."\n";
                    echo "!!! ".$product->id1C." ".$product->isImageMain." ".$product->isImageMore." ".$product->name."\n";  
                    if ($product->getTypeProduct() == Category::TYPE_YARN){
                        echo "delete yarn\n";
                        //$product->delete();
                    }                                      
                }
                $predId = $product->id1C;
            }
        }
    }


    public function findHistoryOff(){
        foreach (History::find()->where(['idEvent'=>2])->all() as $h) {
            $p = Product::findOne($h->idProduct);
            if ($p->getTypeProduct() == Category::TYPE_KIT){
                echo $p->id1C." / ".$p->name."\n";
            }
        }
    }


    public function test(){
        $idCategory = 544;
        foreach (Product::find()->where([ 'idCategory' => $idCategory ])->all() as $product){
            echo $product->getSnapShot()."\n";
        }
    }    


    public function actionIndex($util){
        switch ($util){
            case 'handleProducts': $this->handleProducts(); break;
            case 'mvYarnImage': $this->doMvYarnImage(); break;
            case 'findReadyStart': Product::findReady(true); break;
            case 'findReady': Product::findReady(false); break;            
            case 'findReadyPhoto': Product::findReady(true); break;
            case 'cleanStoreStart': $this->cleanStore(true); break;
            case 'cleanStore': $this->cleanStore(false); break;
            case 'importMebel': $this->importMebel(); break;
            case 'setCatIsReady': $this->setCatIsReady(); break;
            case 'setKitCategory': $this->setKitCategory(); break;
            case 'correctKroshe': $this->correctKroshe(); break;
            case 'checkPrice': $this->checkPrice(); break;
            case 'yandexMarket': $this->export2YandexMarket(); break;
            case 'checkYarnStart': $this->checkYarn(true); break;
            case 'checkYarn': $this->checkYarn(false); break;
            case 'noPrice2ArchStart': $this->noPrice2Arch(true); break;
            case 'noPrice2Arch': $this->noPrice2Arch(false); break; 
            case 'findHistoryOff': $this->findHistoryOff(); break;                       
            case 'findLost': $this->findLost(); break;
            case 'test': $this->test(); break;
        }
    }

}
