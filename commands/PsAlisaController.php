<?php


namespace app\commands;
use yii\console\Controller;
use app\models\ProductColor;

include 'simple_html_dom.php';

$listProduct = array();





class PsAlisaController extends ParseController{
    public $categoryId = 17037;
    public $manufacturerId = 6;
    public $listColors = [];
    public $listProduct = [];
    public $aSku1c;


    public function actionIndex($mode){
        if ($mode == "sku_1c"){
            $this->sku_1c();
        }else{
            $this->parseCatalog('http://www.alisa-collection.ru/?id=224');
            for ($i=2; $i <= 21; $i++){
                $this->parseCatalog('http://www.alisa-collection.ru/?id=224&page224='.$i); 
            }
        }
    }


    public function sku_1c(){
        $f = fopen("db/alisa.spr", "r");
        if ($f) {
            while (($str = fgets($f)) !== false) {
                $str = iconv("CP1251", "UTF-8", $str);
                $a = split(';', $str);
                if (count($a) == 18){
                    $id1c = $a[0];
                    $name = $a[2];                   
                    preg_match('/(\d+-\d+)\s+/', $name, $matches);
                    if (!empty($matches[1])){
                       $sku = $matches[1]; 
                       echo "[ 'id1c' => ".$id1c.", 'sku' => '".$sku."' ],\n";
                    }                    
                }
            }
            if (!feof($f)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($f);
        }
    }



    function checkSku($product){
        preg_match('/(\w+)-/', $product['sku'], $matches);
        if (count($matches) == 2){
            return $matches[1] != 0;
        }
        return false; 
    }



    function parseSet($url){
        $html = file_get_html($url);
        $product = array();

        //'http://www.alisa-collection.ru/?id=19984';
        $product['urlSrc'] = $url;
        if (preg_match('/\?id=(\d+)/', $url, $matches)){
            $product['id'] = $matches[1];       
        }
        foreach($html->find('.block_is') as $div){
            foreach($div->find('h1') as $div_h1){       
                $product['name']  = $div_h1->plaintext;
            }
            foreach($div->find('#content_'.$product['id']) as $divContent){     
                foreach($divContent->find('img') as $img){      
                    $product['imgUrl']  = 'http://www.alisa-collection.ru/'.$img->src;
                    $product['imgFile'] = 'icon.jpg';
                }
                $a = explode(PHP_EOL, $divContent->plaintext);
                foreach ($a as $attr){
                    $attr = trim($attr);                
                    $patterns = array();
                    $patterns[0] = '/&nbsp;/';
                    $replacements = array();
                    $replacements[0] = ' ';
                    if (strlen($attr) > 0){
                        $attr =  preg_replace($patterns, $replacements, $attr);
                        $attr = explode(':', $attr);
                        if (count($attr) == 2){
                            $attrName = trim($attr[0]);
                            $attrVal  = trim($attr[1]);
                            switch ($attrName){
                                case 'Артикул':
                                    $product['sku'] = $attrVal;
                                    $product['id1C'] = $this->findId1c($product['sku']);
                                    break;
                                case 'Размер':
                                    $product['size'] = $attrVal;
                                    break;
                                case 'Техника  вышивания':
                                    $product['tech'] = $attrVal;
                                    break;  
                                case 'Техника вышивания':
                                    $product['tech'] = $attrVal;
                                    break;                                      
                                case 'Канва':
                                    $product['canva'] = $attrVal;
                                    break;  
                                case 'Мулине':
                                    $product['floss'] = $attrVal;
                                    break;  
                                case 'Количество  цветов':
                                    $product['colorCount'] = $attrVal;
                                    break;  
                                case 'Количество цветов':
                                    $product['colorCount'] = $attrVal;
                                    break;                                     
                                case 'Инструкция':
                                    $product['language'] = $attrVal;
                                    break;  
                                case 'Игла':
                                    $product['needle'] = $attrVal;
                                    break;  
                            }
                        }                   
                    }
                }           
            }       
        }
        foreach($html->find('#basket'.$product['id'].'_price') as $div){
            $product['price'] = $div->plaintext;
        }
        $product['weight'] = 0;
        $product['txt'] = '';
        return $product;
    }



    function parseCatalog($url){
        $html = file_get_html($url);

        foreach($html->find('.stdform') as $div){
            foreach($div->find('a.menuchilds') as $a){
                //echo $a->href."\n";
                $product = $this->parseSet('http://www.alisa-collection.ru/'.$a->href);
                $product['idMmanufacturer'] = $this->manufacturerId;
                $product['idCategory'] = $this->categoryId;
                //if ($this->checkSku($product)){
                    print_r($product);
                    $this->insertProduct($product);                   
                //}
            }
        }
    }


}


?>