<?php


namespace app\commands;
use yii\console\Controller;
use app\models\Product;
use app\models\ProductKit;

include 'simple_html_dom.php';

$listProduct = array();


class PsGoldController extends ParseController{
    public $categoryId = 544;
    public $idCategory = 544;    
    public $manufacturerId = 48;
    public $idManufacturer = 48;    
    public $aSku = [];
    public $aCategory = [];
    public $aSku1c;

	const SKU_NONE 	= 0;
	const SKU_CHAR_NUMBER 	= 1;
	const SKU_NUMBER 		= 2;
	const SKU_NUMBER_NUMBER = 3;

    public function init(){

	}


	function checkSku($product){
		preg_match('/([а-яёА-Я]+)-(\d+)/', $product['sku'], $matches);
		// print_r($matches);
		echo "count == ".count($matches)."\n";
		if (count($matches) == 3){
			$skuChar = $matches[1];
			$skuNum = $matches[2];
			foreach ($this->aSku as $itemSku){
				if ($itemSku['char'] == $skuChar){
					foreach ($itemSku['number'] as $n){
						if ($n == $skuNum){
							return true;
						}
					}
				}
			}
		}
		return false;
	}



	public function get1cFromSku($product){
    	$sku = [];
    	$strSku = '';
		if (preg_match('/^([а-яёА-Я]+)-(\d+)/', $product['sku'], $matches)) {
			$sku['type'] = self::SKU_CHAR_NUMBER;
			$sku['char'] = $matches[1];
			$sku['number'] = $matches[2]; 
			$strSku = $sku['type']."/".$sku['char']."/".$sku['number'];							
		}
		if (count($sku) == 0){
			$sku['type'] = self::SKU_NONE;
			echo "Error: sku == ".$product['sku']." / name == ".$product['name']."no detect sku!\n";
		}else{
			echo "			sku == ".$product['sku']." ".$strSku."\n";
		}
		//print_r($sku);
    	foreach ($this->aSku1c as $sku1c){
			if ($sku['type'] == $sku1c['type']){
				switch ($sku['type']) {
					case self::SKU_CHAR_NUMBER:
						if ((int)$sku['number'] == (int)$sku1c['number'] && $sku['char'] == $sku1c['char']){
							return $sku1c['1c'];
						}
						break;										
				}
			}	    		
    	}
    }



	function parseSet($url){
		$aKey = [
			[ 'productKey' => 'author',  	'key' => 'Дизайнер' ],
			[ 'productKey' => 'size',  		'key' => 'Размер' ],
			[ 'productKey' => 'canva',  	'key' => 'Канва' ],		
			[ 'productKey' => 'colorCount',  'key' => 'Количество цветов' ],				
		];
		$html = file_get_html($url);
		$product = [];
		$product['urlSrc'] = $url;
		$div = $html->find('.title', 0);
		$h1 = $div->find('h1', 0);
		echo "h1 == ".$h1->plaintext."\n";  
		if (preg_match('/МК-021/', $h1->plaintext)) {
			$matches[1] = "МК-021";
			$matches[2] = "Девушка с жемчужной сережкой";  
		}elseif(preg_match('/МТ-002/', $h1->plaintext)){
			$matches[1] = "МТ-002";
			$matches[2] = "Год лошади";
		}else{
			preg_match('/([а-яёА-Я]+-\d+)\s+(.+)/', $h1->plaintext, $matches);
			if (count($matches) == 0){
				preg_match('/([а-яёА-Я]+-\d+\/\d+)\s+(.+)/', $h1->plaintext, $matches);
			}
		}
		if (count($matches) == 0){
			$product['sku']  = '';
			$product['name'] = $h1->plaintext;
		}else{
			$product['sku']  = $matches[1];
			$product['name'] = $matches[2];			
		}
		//$product['id1C'] = $this->findId1c($product['sku']);
		$product['colorCode'] = 0;		

		foreach ($html->find('.mainImg') as $div) {
			foreach ($div->find('img') as $img) {
				$patterns = array();
				$patterns[0] = '/\/\/www/';
				$replacements = array();
				$replacements[0] = 'www';
				$product['imgUrl'] =  preg_replace($patterns, $replacements, $img->src);
				if (preg_match('/jpg/', $img->src)){
					preg_match('/\/([\d\w\.\?-_]+)$/', $img->src, $matches);						
				}
				if (preg_match('/jpeg/', $img->src)){
					preg_match('/\/([\d\w\.\?-_]+)$/', $img->src, $matches);			
				}

				$product['imgFile'] = '';
				if (count($matches) == 2){
					//echo "matches[1] == ".$img->src."\n";
					$product['imgFile'] = $matches[1];
				}
			}
		}

		foreach ($html->find('.bx_item_description') as $div) {
			$a = explode("\n", $div->plaintext);
			$isTxt = 0;
			$product['content'] = '';
			foreach ($a as $str){
				$str = trim($str);
				if (strlen($str) == 0){
					$isTxt = 1;
				}else{
					if ($isTxt){
						$product['content'] .= $str;
					}else{
						if ($this->findKey($str, $aKey, $key, $val)){
							$product[$key] = $val;
						}else{												
							if (preg_match('/Вышивается/', $str, $matches)){
								$product['tech'] = $str;
							}
							if (preg_match('/В набор включается/', $str, $matches)){
								$isTxt = 1;
							}
						}
					}
				}
			}
		}
		$product['txt'] = '';
		$product['isOrder'] = false;
		foreach ($html->find('.longDesc') as $div) {
			foreach ($div->find('dl') as $dl) {
				$aDl = $dl->children();
				for ($i=0; $i < count($aDl); $i+=2){
					$key = trim($aDl[$i]->plaintext);
					$val = trim($aDl[$i+1]->plaintext);	
					// echo ('Актуальность' == $key). " ".$key."\n"; 
					// echo ('Под заказ' == $val). " ".$val."\n";
					if ('Актуальность' == $key && 'Под заказ' == $val){
						$product['isOrder'] = true;
					}
					if ('Серия' == $key){
						$product['categoryName'] = $val;
					}				
				}
			}
		}
		$div = $html->find('.priceBlock', 0);
		preg_match('/\s+Цена\s+([\d\w&;]+)\s+/', $div->plaintext, $matches);
		$patterns = array();
		$patterns[0] = '/&nbsp;/';
		$replacements = array();
		$replacements[0] = '';
		$product['price'] =  preg_replace($patterns, $replacements, $matches[1]);
	  	return $product;
	}


	function getCategoryId($product){
		global  $parent_id;
		foreach ($this->aCategory as $cat){
			if ($cat['name'] == $product['categoryName']){
				return $cat['id'];
			}
		}
		$catData = [
			'parent_id' => $parent_id,
			'name' => $product['categoryName'],
			'txt' => '',
		];
		insertCategory($catData);
		preg_match('/([а-яёА-Я]+)-\d+/', $product['sku'], $matches);
		array_push($this->aCategory, [
				'name' => $product['categoryName'],
				'id' => $catData['category_id'],
				'charSku' => $matches[1]
			]);
		// echo "aCatData == ";
		// print_r($catData);
		// echo "\n";	
		// echo "aCategory == ";	
		// print_r($aCategory);
		// echo "\n";		
		return $catData['category_id'];
	}


	function parseCatalog($url){
		$html = file_get_html($url);

		foreach($html->find('.item') as $div){
			$h4 = $div->find('h4', 0);
			$a = $h4->find('a', 0);
			$product = $this->parseSet('http://www.rukodelie.ru'.$a->href);
			$product['idManufacturer'] = $this->manufacturerId;
			$product['idCategory'] = $this->categoryId;  //$this->getCategoryId($product);
			echo "product == ";
			print_r($product);
			$this->saveProductKit($product);	
			sleep(1);	
		}
	}
	

	function parseCategory($url){
		$html = file_get_html($url);

		foreach($html->find('.bx_filter_parameters_box_container') as $div){
			foreach ($div->find('span') as $span) {
				echo trim($span->plaintext)."\n";	
			}
		}
	}



    public function rightProductKitSku(){
        foreach (ProductKit::find()->all() as $productKit) {
            // $productKit->skuOriginal = $productKit->sku;
            // $productKit->save();
            // echo $productKit->skuOriginal." -> ".$productKit->sku."\n";
            if (preg_match('/([а-яёА-Я]+)(\d+)\/(\d+)\s*/', $productKit->skuOriginal, $matches)){
                $sku = $matches[1].'-'.$matches[2].'-'.$matches[3];
            }elseif (preg_match('/([а-яёА-Я]+)(\d+)\s*/', $productKit->skuOriginal, $matches)) {
                $sku = $matches[1].'-'.$matches[2];
            }elseif (preg_match('/(\d+)([а-яёА-Я]+)\s*/', $productKit->skuOriginal, $matches)){
                $sku = $matches[2].'-'.$matches[1];
            }elseif (preg_match('/(\d+)\s+([а-яёА-Я]+)\s*/', $productKit->skuOriginal, $matches)){
                $sku = $matches[2].'-'.$matches[1]; 
            }elseif (preg_match('/([а-яёА-Я]+)\s+(\d+)\s*/', $productKit->skuOriginal, $matches)){
                $sku = $matches[1].'-'.$matches[2];
            }elseif (preg_match('/(\d+)\/(\d+)\s*/', $productKit->skuOriginal, $matches)){
                $sku = $matches[1].'-'.$matches[2];
            }elseif (preg_match('/(\d+)\s*/', $productKit->skuOriginal, $matches)){
                $sku = $matches[1];
            }else{
                $sku = "???".$productKit->skuOriginal;
            }
            $productKit->sku = $sku;
            $productKit->save();
            //echo $productKit->skuOriginal." / "."sku == ".$sku."\n";
        }
    }


    public function rightProductSku(){
        foreach (Product::find()->where(['idCategory' => $this->idCategory, 'isReady' => 0])->all() as $product) {
            // $productKit->skuOriginal = $productKit->sku;
            // $productKit->save();
            if (strlen($product->sku) < 2){
                echo $product->sku." -> ".$product->name."\n";
            }
            // continue;

            $name = $product->nameFull;
            if (preg_match('/^([а-яёА-Я]{1,4})-(\d+)\s+/', $name, $matches)) {
                $sku = $matches[1].'-'.$matches[2];                                                       
            }else{
                $sku = '';
            }
            if (strlen($sku) > 0){
                $product->sku = $sku;
                $product->save();               
            }
            //echo $productKit->skuOriginal." / "."sku == ".$sku."\n";  
        }
    }


    public function handleSku(){
        $this->rightProductSku();
    }



    public function handleLink(){
        $countOk = 0;
        $countErr = 0;
        foreach (Product::find()->where(['idCategory' => $this->idCategory, 'isReady' => 0])->all() as $product) {
            if (strlen($product->sku) > 0){
                if ($productKit = ProductKit::find()->where(['sku' => $product->sku, 'idManufacturer' => $this->idManufacturer])->one()){
                    echo "link ok: ".$product->name." / ".$productKit->name."\n"; 
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
                    $product->save();
                    $productKit->id1C = $product->id1C;
                    $productKit->save();
                    $this->cpImageKit($product, $productKit);
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


    public function actionIndex($mode){
    	if ($mode == 'fill'){
			// $this->parseCatalog('http://www.rukodelie.ru/catalog/Nabory_dlya_vyshivaniya/nabory_dlya_vyshivaniya_krestom/');
			// for ($i=2; $i <= 92; $i++) { 
			// 	$this->parseCatalog('http://www.rukodelie.ru/catalog/Nabory_dlya_vyshivaniya/nabory_dlya_vyshivaniya_krestom/?PAGEN_1='.$i);			
			// }    

			$this->parseCatalog('http://www.rukodelie.ru/catalog/Nabory_dlya_vyshivaniya/nabory_dlya_vyshivaniya_biserom/');
			for ($i=2; $i <= 13; $i++) { 
				$this->parseCatalog('http://www.rukodelie.ru/catalog/Nabory_dlya_vyshivaniya/nabory_dlya_vyshivaniya_biserom/?PAGEN_1='.$i);			
			}  			    		
    	}
    	// if ($mode == 'sku_1c'){
    	// 	$this->getSku();
    	// }
        if ($mode == 'handleSku'){
            $this->handleSku();
        }   
        if ($mode == 'handleLink'){
            $this->handleLink();
        }             
	
    }


	public function getSku(){
		$kolEmpty = 0;
		$kolOk = 0;
		foreach (Product::find()->where(['idCategory' => $this->categoryId])->all() as $product){
			$sku = [];
			$name = $product->nameFull;
			if (preg_match('/^([а-яёА-Я]+)-(\d+)/', $name, $matches)) {
				$sku['type'] = self::SKU_CHAR_NUMBER;
				$sku['char'] = $matches[1];
				$sku['number'] = $matches[2]; 											
			}else{
				//echo "bad sku: ".$name."\n";
			}				
			if (count($sku) == 0){
				$kolEmpty++;
			}else{
				$kolOk++;
				echo "[ 'type' => ".$sku['type'].", '1c' => '".$product['id1C']."',";
				switch ($sku['type']) {
					case self::SKU_CHAR_NUMBER:
						echo " 'char' => '".$sku['char']."', 'number' => '".$sku['number']."' ],\n";
						break;
					case self::SKU_NUMBER_NUMBER:
						echo " 'number1' => '".$sku['number1']."', 'number2' => '".$sku['number2']."' ],\n";
						break;		
					case self::SKU_NUMBER:
						echo " 'number' => '".$sku['number']."' ],\n";
						break;											
				}
			}
		}
	}


}
