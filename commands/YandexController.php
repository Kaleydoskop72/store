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

class YandexController extends Controller{

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
  <option cost="200" days="1-2"/>
</delivery-options>
<cpa>0</cpa>

END;
        return $yandex;
    }


    public function yandexOffer($p, $c){
        $url = 'http://tkanirukodelie.ru/'.$c->seoURL.'/'.$p->seoURL;
        //$url = 'http://tkanirukodelie.ru/index.php?route=product/product&path='.$c->id.'&product_id='.$p->id;
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
        $name = $p->nameYandex;
        $brand = '';
        if ($man = Manufacturer::findOne($p->idManufacturer)){
            $brand = strtolower($this->removeUnwantedChars($man->name));
        }
        $model = '';
        $description = $this->removeUnwantedChars($p->description);

        $yandex = <<<END
    <offer id="$p->id" available="true">
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
        <store>true</store>
        <delivery>true</delivery>
        <pickup>true</pickup>
        <name>$name</name>
        <vendor>$brand</vendor>
        <model>$model</model>

END;
        if (strlen($description) > 0){
            $yandex .= '        <description>'.$description."</description>\n";
        }
        $yandex .= <<<END
        <age>0</age>
        <manufacturer_warranty>false</manufacturer_warranty>

END;
        $yandex .= '        <param name="Тип">набор для вышивания</param>'."\n";
        if (strlen($p->floss)){
            $yandex .= '        <param name="Мулине">'.$this->removeUnwantedChars($p->floss).'</param>'."\n";
        }
        if (strlen($p->canva)){
            $yandex .= '        <param name="Канва">'.$this->removeUnwantedChars($p->canva).'</param>'."\n";
        }        
        if (strlen($p->colorCount)){
            $yandex .= '        <param name="Количество цветов">'.$this->removeUnwantedChars($p->colorCount).'</param>'."\n";
        }     
        if (strlen($p->needle)){
            $yandex .= '        <param name="Игла">'.$this->removeUnwantedChars($p->needle).'</param>'."\n";
        }  
        if (strlen($p->tech)){
            $yandex .= '        <param name="Техника вышивания">'.$this->removeUnwantedChars($p->tech).'</param>'."\n";
        }    
        if (strlen($p->thread)){
            $yandex .= '        <param name="Нитки">'.$this->removeUnwantedChars($p->thread).'</param>'."\n";
        }      
        if (strlen($p->sizePack)){
            $yandex .= '        <param name="Размер упаковки">'.$this->removeUnwantedChars($p->sizePack).'</param>'."\n";
        }                                               
        $yandex .= "    </offer>\n"; 
        return $yandex;
    }


    public function yandexOffers(){
        $yandex = "<offers>\n";
        foreach (Product::find()->where(['isReady' => 1, 'isExportMarket' => 1])->all() as $product){
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
        $yandex  = "</shop>\n";
        $yandex .= "</yml_catalog>\n";      
        return $yandex;
    }

    
    public function export2YandexMarket(){
        $yandex = $this->yandexHeader();
        $yandex .= $this->yandexCategory();
        $yandex .= $this->yandexDeliveryCpa();
        $yandex .= $this->yandexOffers();
        $yandex .= $this->yandexEnd();       
        // $f = fopen("../../tkanirukodelie.ru/public_html/yandex.yml", "w");
        $f = fopen("./yandex.yml", "w");
        //echo "yandex == ".$yandex."\n";
        fwrite($f, $yandex);
        fclose($f);
    }


    public function translite($string){
        $replaceRus = array(
            "А"=>"A","а"=>"a",
            "Б"=>"B","б"=>"b",
            "В"=>"V","в"=>"v",  
            "Г"=>"G","г"=>"g",
            "Д"=>"D","д"=>"d",
            "Е"=>"Ye","е"=>"e",
            "Ё"=>"Ye","ё"=>"e",
            "Ж"=>"Zh","ж"=>"zh",
            "З"=>"Z","з"=>"z",
            "И"=>"I","и"=>"i",
            "Й"=>"Y","й"=>"y",
            "К"=>"K","к"=>"k",
            "Л"=>"L","л"=>"l",
            "М"=>"M","м"=>"m",
            "Н"=>"N","н"=>"n",
            "О"=>"O","о"=>"o",
            "П"=>"P","п"=>"p",
            "Р"=>"R","р"=>"r",
            "С"=>"S","с"=>"s",
            "Т"=>"T","т"=>"t",
            "У"=>"U","у"=>"u",
            "Ф"=>"F","ф"=>"f",
            "Х"=>"Kh","х"=>"kh",
            "Ц"=>"Ts","ц"=>"ts",
            "Ч"=>"Ch","ч"=>"ch",
            "Ш"=>"Sh","ш"=>"sh",
            "Щ"=>"Shch","щ"=>"shch",
            "Ъ"=>"","ъ"=>"",
            "Ы"=>"Y","ы"=>"y",
            "Ь"=>"","ь"=>"",
            "Э"=>"E","э"=>"e",
            "Ю"=>"Yu","ю"=>"yu",
            "Я"=>"Ya","я"=>"ya",
        );
        $string = preg_replace('/\s+/i', ' ', $string); 
        $string = preg_replace('/^\s+/i', '', $string);  
        $string = preg_replace('/\s+$/i', '', $string);          
        $string = preg_replace('/\(.+\)/i', '', $string);              
        $string = strtr($string, $replaceRus);
        $string = preg_replace('/\d+%/i', '', $string);
        $string = preg_replace('/\w+\/\w+\/\w+/i', '', $string);        
        $string = preg_replace('/\w+\/\w+/i', '', $string);
        $string = preg_replace('/\s+/i', '_', $string); 
        return strtolower($string);
    }


    public function makeSeo(){
        // foreach (Product::find()->where([ 'idParent' => 0, 'isReady' => 1 ])->all() as $product){
        //     $name = $this->translite($product->name);
        //     $typeProduct = $product->getTypeProduct();
        //     if ($typeProduct == Category::TYPE_KIT){
        //         $manufacturerName = '';
        //         if ($man = Manufacturer::findOne($product->idManufacturer)){
        //             $manufacturerName = $man->name;
        //         }
        //         $name = $this->translite($manufacturerName).'-'.$name;
        //     }
        //     echo "nameFull: ".$product->nameFull."\n";
        //     echo "name: ".$product->name."\n";
        //     echo "name: ".$name."\n";
        //     echo "\n";
        // }
    }


    public function getNameProduct($string){
        if (preg_match('/"(.+)"/U', $string, $matches)){
            if (count($matches) == 2){
                return $matches[1];
            }
        }
        $string = preg_replace('/\(.+\)/i', '', $string);
        $string = preg_replace('/\s+см/i', '', $string);
        $string = preg_replace('/2-х\s+сторонняя/i', '', $string);
        $string = preg_replace('/\s+ш\s+\d+/i', '', $string);
        $string = preg_replace('/\s+Ш\s+\d+/i', '', $string);
        $string = preg_replace('/\s+ширина\s+\d+/i', '', $string);
        $string = preg_replace('/\d+%/i', '', $string);
        $string = preg_replace('/\s+цв\..+$/i', '', $string);        
        $string = preg_replace('/\s+цв\..+\s+/i', '', $string);        
        $string = preg_replace('/\s+\d+\s+/i', '', $string);     
        $string = preg_replace('/[а-яА-Я]*\d+[а-яА-Я]*/i', '', $string);      
        $string = preg_replace('/\w+\/\w+\/\w+/i', '', $string);
        $string = preg_replace('/\w+\/\w+/i', '', $string);
        return $this->removeUnwantedChars($string);
    }

// Б1020 Ксения Петербургская наб/выш "Паутинка" 20*26,5 см 16 цветов

    public function makeNameProductPautinka($string){
        if (preg_match('/[а-яА-Я]+\d+\s+(.+)\s+наб\//', $string, $matches)){
            if (count($matches) == 2){
                return $matches[1];
            }
        } 
        return $string;
    }


    public function removeUnwantedChars($string){
        $replaceChars = array(
            '"'=>'', '.'=>'', ','=>'',
            '!'=>'', '*'=>'', "'"=>'',
            ';'=>'', '@'=>'', '&'=>'',
            '='=>'', '+'=>'', '$'=>'',
            '/'=>'', '?'=>'', '#'=>'',
            '['=>'', ']'=>'', '('=>'', 
            ')'=>'', '%'=>''
        );
        // foreach ($exclude as $cEclude) {
        //     for ($i=0; $i < count($replaceChars); $i++){
        //         if ($replaceChars[$i])
        //     }
        // }
        $string = preg_replace('/\s+/i', ' ', html_entity_decode($string));        
        return strtr($string, $replaceChars);
    }


    public function mkYandexNameProduct($product){
        $typeName = '';
        switch ($product->getTypeProduct()) {
            case Category::TYPE_FABRIC:
                $typeName = 'ткань ';
                break;
            case Category::TYPE_KIT:
                $typeName = 'набор для вышивания ';
                // $typeName = 'вышивка';
                break;        
            case Category::TYPE_YARN:
                $typeName = 'пряжа ';
                break;                                
        }
        $manufacturerName = '';
        if ($m = Manufacturer::findOne($product->idManufacturer)){
            $manufacturerName = $this->removeUnwantedChars($m->name);
            $manufacturerName = ucfirst(strtolower($manufacturerName));
        }
        return $typeName.' '.$manufacturerName.' '.$product->nameProduct;
    }



    public function mkTitleNameProduct($product){
        $typeName = '';
        $manufacturerName = '';
        if ($m = Manufacturer::findOne($product->idManufacturer)){
            $manufacturerName = $this->removeUnwantedChars($m->name);
            $manufacturerName = ucfirst(strtolower($manufacturerName));
        }        
        switch ($product->getTypeProduct()) {
            case Category::TYPE_FABRIC:
                $typeName = 'ткань ';
                $title = $typeName.' '.$manufacturerName.' '.$product->nameProduct;
                break;
            case Category::TYPE_KIT:
                $typeName = 'вышивка';
                $title = $typeName.' '.$manufacturerName.', наборы для вышивания '.$manufacturerName.', '.$product->nameProduct;
                break;        
            case Category::TYPE_YARN:
                $typeName = 'пряжа ';
                $title = $typeName.' '.$manufacturerName.' '.$product->nameProduct;
                break;                                
        }

        return $title;
    }



    public function makeName(){
        foreach (Product::find()->where([ 'idParent' => 0, 'isReady' => 1])->all() as $product){
            $name = $product->nameFull;
            if (strlen($name) == 0){
                $name = $product->name;
            }
            if (($typeProduct = $product->getTypeProduct()) == Category::TYPE_FABRIC){
                $name = $product->name;
            }
            if ($product->idCategory == 18988){
                $name = $this->makeNameProductPautinka($product->nameFull);
            }else{
                $name = $this->getNameProduct($name);
            }
            $product->nameProduct = $this->removeUnwantedChars($name);
            $product->nameYandex = $this->mkYandexNameProduct($product);

            $product->save();
        }
        foreach (Category::find()->where([ 'isReady' => 1 ])->all() as $cat){
            $name = $this->getNameProduct($cat->name);
            $cat->nameCategory = strtolower($this->removeUnwantedChars($name));
            $cat->save();
        }
    }


    public function makeSeoUrl(){
        foreach (Product::find()->where([ 'idParent' => 0, 'isReady' => 1])->all() as $product){
            $name = $this->translite($product->nameProduct).'-'.$product->id;
            // $typeProduct = $product->getTypeProduct();
            // if ($typeProduct == Category::TYPE_KIT){
            //     if ($man = Manufacturer::findOne($product->idManufacturer)){
            //         $manufacturerName = $this->translite($this->getNameProduct($man->name));
            //         $name = $manufacturerName.'-'.$name;
            //     }                
            // }
            $product->seoURL = $name;        
            echo $product->name."\n";
            echo $product->nameProduct."\n";
            echo $product->seoURL."\n";
            echo "\n";
            $product->save();
        }
        foreach (Category::find()->where([ 'isReady' => 1 ])->all() as $cat){
            $cat->seoURL = $this->translite($cat->nameCategory).'-'.$cat->id;
            $cat->save();
        }        
    }


    public function makeTitleProduct($isFirst){
        echo "in makeTitleProduct() \n";
        if ($isFirst){
            Product::updateAll(['isHandled' => 0]);
        }
        $list = Product::find()->where([ 'isReady' => 1, 'idParent' => 0, 'isHandled' => 0])->limit(2000)->all();
        if (count($list) == 0){
            echo "ok\n";
            return;
        }        
        foreach ($list as $product){
            $manufacturerName = "";
            if ($manufacturer = Manufacturer::findOne($product->idManufacturer)){
                $manufacturerName = $manufacturer->name;
            }
            $title = 'Тюмень, ';
            switch ($product->getTypeProduct()) {
                case Category::TYPE_KIT:
                    $title .= ' '.self::mkTitleNameProduct($product);
                    // $title .= ''.$product->nameYandex;
                    break;
                case Category::TYPE_YARN:                    
                    $title .= ''.$product->name;
                    break;  
                case Category::TYPE_FABRIC:
                    $title .= ''.$product->name;
                    break;                                      

            }
            if (strlen($title) > 0){
                $title .= ' интернет-магазин ТканиРукоделие';
            }
            //echo $title."\n";
            $product->title = $title;
            $product->isHandled = 1;
            if (!$product->save()){
                print_r($product->getErrors());
            }
        }
    }    


    public function makeTitleCategory(){
        // Тюмень, набор для вышивания  Riolis Натюрморт с красным вином интернет-магазин ТканиРукоделие
        echo "in makeTitleCategory() \n";
        foreach (Category::find()->where(['isReady' => 1])->all() as $cat){
            echo $cat->name."\n";
            if ($cat->idParent != 0){
                $catParent = Category::findOne($cat->idParent);
                $title = 'Тюмень, '.' вышивка '.$cat->name.' '.$catParent->name.' '.$cat->name;
            }else{
                // $title = 'Тюмень, '.$cat->name;
                $title = 'Тюмень, '.' вышивка '.$cat->name.' '.$catParent->name.' '.$cat->name;
            }
            // $title = 'купить набор для вышивания '.$cat->name;
            // $title = '';
            // switch ($cat->typeProduct) {
            //     case Category::TYPE_KIT:
            //         $title = 'купить набор для вышивания '.$cat->name;
            //         break;
            //     case Category::TYPE_YARN:
            //         $title = 'купить пряжу '.$product->name;
            //         break;  
            //     case Category::TYPE_FABRIC:
            //         $title = 'купить '.$product->name;
            //         break;                                      
            // }
            if (strlen($title) > 0){
                $title .= ' интернет-магазин ТканиРукоделие';
            }
            $cat->title = $title;
            $cat->save();
        }
    }    


    public function actionIndex($util){
        switch ($util){
            case 'makeName': $this->makeName();   break;
            case 'makeSeoUrl':      $this->makeSeoUrl();        break;
            case 'yandexMarket':    $this->export2YandexMarket(); break;
            case 'makeTitleProductStart': $this->makeTitleProduct(true); break;
            case 'makeTitleProduct':   $this->makeTitleProduct(false); break;    
            case 'makeTitleCategory':   $this->makeTitleCategory(); break;                    
        }
    }

}
