<?php


namespace app\commands;
use yii\console\Controller;
use app\models\Product;
use app\models\ProductKit;

include 'simple_html_dom.php';

$listProduct = array();


class PkAlisaController extends ParseController{

	const SKU_NONE 	= 0;
	const SKU_CHAR_NUMBER 	= 1;
	const SKU_NUMBER 		= 2;
	const SKU_NUMBER_NUMBER = 3;

	public $idCategory = 17037;
	public $idManufacturer = 56;


	public function handleLink(){
		// $productKit = ProductKit::find()->where(['sku' => '5-10', 'idManufacturer' => $this->idManufacturer])->one();
		// print_r($productKit);
		// return;
		$countOk = 0;
		$countErr = 0;
		foreach (Product::find()->where(['idCategory' => $this->idCategory])->all() as $product) {
			if (strlen($product->sku) > 0){
				echo $product->name." / !".$product->sku."! / ".$this->idManufacturer."\n";
				if ($productKit = ProductKit::find()->where(['sku' => $product->sku, 'idManufacturer' => $this->idManufacturer])->one()){
					//echo "link ok: ".$product->name." / ".$productKit->name."\n";
					$product->description = $productKit->description;
					$product->thread = $productKit->thread;
					$product->size = $productKit->size;
					$product->sizePack = $productKit->sizePack;
					$product->canva = $productKit->canva;
					$product->author = $productKit->author;
					$product->language = $productKit->language;
					$product->colorCount = $productKit->colorCount;
					$product->needle = $productKit->needle;
					$product->booklet = $productKit->booklet;
					$product->design = $productKit->design;
					$product->weight = $productKit->weight;
                    $product->floss  = $productKit->floss;                    
					if (!$product->save(false)){
                        print_r($product->getErrors());
                    }
                    echo "id == ".$product->id." floss == ".$product->floss."\n";
					$productKit->id1C = $product->id1C;
					$productKit->save();
					//$this->cpImageKit($product, $productKit);
					$countOk++;
				}else{
					echo "!!! not link: ".$product->id1C." / ".$product->nameFull."\n"; 
					$countErr++;
				}
			}else{
				echo "!!! not sku: ".$product->id1C." / ".$product->nameFull."\n"; 
				$countErr++;	
			}		
		}
		echo $countOk." / ".$countErr."\n";
	}


	public function handleSku(){
		foreach (Product::find()->where(['idCategory' => $this->idCategory])->all() as $product) {
			echo "> ".$product->name."\n";
			if (!preg_match('/([а-яёА-Я0-9]+)-(\d+)/', $product->name, $matches)){
				echo "!!! no found sku \n";
			}
			print_r($matches);
		 	if (count($matches) == 3){
		 		echo $matches[1]." / ".$matches[2]."\n";
		 		$product->sku = $matches[1]."-".$matches[2];
		 		$product->save();
		 	}else{
		 		echo "!!! Error link sku: ".$product->name."\n";
		 	}
		}
	}


    function parseSet($url){
        $html = file_get_html($url);
        $product = array();

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
                                    $product['numColors'] = $attrVal;
                                    break;  
                                case 'Количество цветов':
                                    $product['numColors'] = $attrVal;
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
        // foreach($html->find('#basket'.$product['id'].'_price') as $div){
        //     $product['price'] = $div->plaintext;
        // }
        $product['price'] = 0;
        $product['weight'] = '';
        $product['txt'] = '';
        return $product;
    }



    function parseCatalog($url){
        $html = file_get_html($url);

        foreach($html->find('.stdform') as $div){
            foreach($div->find('a.menuchilds') as $a){
                $product = $this->parseSet('http://www.alisa-collection.ru/'.$a->href);
                $product['idManufacturer'] = $this->idManufacturer;
                $product['idCategory'] = $this->idCategory;
                print_r($product);
                $this->saveProductKit($product);
				sleep(4);                 
            }
        }
    }



    public function actionIndex($mode){
    	if ($mode == 'parse'){
            $this->parseCatalog('http://www.alisa-collection.ru/?id=224');
            for ($i=2; $i <= 24; $i++){
            	echo "***************************".$i."***************************\n";
                $this->parseCatalog('http://www.alisa-collection.ru/?id=224&page224='.$i); 
            }	 
    	}
    	if ($mode == 'handleSku'){
    		$this->handleSku();
    	}
    	if ($mode == 'handleLink'){
    		$this->handleLink();
    	}
    }


}

