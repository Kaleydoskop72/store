<?php


namespace app\commands;
use yii\console\Controller;
use app\models\Product;
use app\models\ProductKit;

include 'simple_html_dom.php';

$listProduct = array();


class PsRiolisController extends ParseController{

	const SKU_NONE 	= 0;
	const SKU_CHAR_NUMBER 	= 1;
	const SKU_NUMBER 		= 2;
	const SKU_NUMBER_NUMBER = 3;

	public $idCategory = 454;
	public $idManufacturer = 47;



    public function get1cFromSku($product){
    	$sku = [];
    	$strSku = '';
		if (preg_match('/^([а-яёА-Я]+)-(\d+)/', $product['sku'], $matches)) {
			$sku['type'] = self::SKU_CHAR_NUMBER;
			$sku['char'] = $matches[1];
			$sku['number'] = $matches[2]; 
			$strSku = $sku['type']."/".$sku['char']."/".$sku['number'];
		}elseif(preg_match('/^(\d+)\/(\d+)/', $product['sku'], $matches)){
			$sku['type'] = self::SKU_NUMBER_NUMBER;				
			$sku['number1'] = $matches[1];
			$sku['number2'] = $matches[2];
			$strSku = $sku['type']."/".$sku['number1']."/".$sku['number2'];
		}elseif(preg_match('/^(\d+)\s+([а-яёА-Яa-zA-Z]+)/', $product['sku'], $matches)){
			if (strlen($matches[2]) <= 4){
				$sku['type'] = self::SKU_CHAR_NUMBER;				
				$sku['number'] = $matches[1];
				$sku['char'] = $matches[2];		
				$strSku = $sku['type']."/".$sku['char']."/".$sku['number'];			
			}elseif(preg_match('/^(\d+)/', $product['sku'], $matches)){
				$sku['type'] = self::SKU_NUMBER;				
				$sku['number'] = $matches[1];	
				$strSku = $sku['type']."/".$sku['number'];												
			}				
		}elseif(preg_match('/^(\d+)/', $product['sku'], $matches)){
			$sku['type'] = self::SKU_NUMBER;				
			$sku['number'] = $matches[1];		
			$strSku = $sku['type']."/".$sku['number'];										
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
					case self::SKU_NUMBER_NUMBER:
						if ((int)$sku['number1'] == (int)$sku1c['number1'] && (int)$sku['number2'] == (int)$sku1c['number2']){
							return $sku1c['1c'];
						}
						break;	
					case self::SKU_NUMBER:
						if ((int)$sku['number'] == (int)$sku1c['number']){
							return $sku1c['1c'];
						}
						break;											
				}
			}	    		
    	}
    }


	function parseSet($url){
		$html = file_get_html($url);
		$product = array();

		//'http://www.riolis.ru/catalog/details_3367.html'
	 	if (preg_match('/details_(\d+)\./', $url, $matches)){
	 		$product['id'] = $matches[1];		
	 	}
	 	$product['price'] = 0;
		foreach($html->find('.v_price') as $div_price){		
		 	if (preg_match('/(.+)&nbsp;/', $div_price->plaintext, $matches)){
		 		$product['price'] = $matches[1];		
		 	}	    		 			
		}
		$product['idManufacturer'] = $this->idManufacturer;
		foreach($html->find('.detail_descr') as $div_descr){		  	
		 	foreach($div_descr->find('li') as $li){
		 		// foreach ($li->find('span') as $span) {
		 		// 	$attrName = $span->plaintext;
		 		// }
		 		// foreach ($li->find('b') as $b) {
		 		// 	$attrVal = $b->plaintext;
		 		// }
		 		// echo "---".$li->plaintext."\n";
		 		// preg_match('/(\.+):(\.+)/', $li->plaintext, $matches);

		 		$matches = split(':', $li->plaintext);
		 		// print_r($matches);	
		 		$attrName = "";
		 		$attrVal = "";		 		
		 		if (count($matches) == 2){
			 		$attrName = trim($matches[0]);
			 		$attrVal  = trim($matches[1]);
		 		}	 		
				$patterns = array();
				$patterns[0] = '/&nbsp;/';
				$replacements = array();
				$replacements[0] = ' ';
				$attrVal =  preg_replace($patterns, $replacements, $attrVal);
		 		switch (trim($attrName)){
		 			case 'Наименование':
		 				$product['name'] = $attrVal;
		 				break;
		 			case 'Артикул':
		 				echo "sku1 == ".$attrVal."\n";
						$patterns = array();
						$patterns[0] = '/№/';
						$replacements = array();
						$replacements[0] = '';
						$attrVal =  preg_replace($patterns, $replacements, $attrVal);	

						$patterns = array();
						$patterns[0] = '/ВК-/';
						$replacements = array();
						$replacements[0] = '';
						$attrVal =  preg_replace($patterns, $replacements, $attrVal);						 				
						$attrVal = self::correctSku($attrVal);
		 				$product['sku'] =  $attrVal;
		 				echo "sku2 == ".$attrVal."\n"; 	
		 				break;
		 			case 'Каталог':
		 				$product['numCatalog'] = $attrVal;
		 				break;
		 			case 'Нитки':
		 				$product['thread'] = $attrVal;
		 				break;
		 			case 'Цветов':
		 				$product['numColors'] = $attrVal;
		 				break;	 								
		 			case 'Игла':
		 				$product['needle'] = $attrVal;
		 				break;
		 			case 'Размер':
		 				$product['size'] = $attrVal;
		 				break;
		 			case 'Канва':
		 				$product['canva'] = $attrVal;
		 				break;
		 			case 'Буклет':
		 				$product['booklet'] = $attrVal;
		 				break;
		 			case 'Художник':
		 				$product['author'] = $attrVal;
		 				break;
		 			case 'Оформление':
		 				$product['design'] = $attrVal;
		 				break;		 				
		 			case 'Язык':
		 				$product['language'] = $attrVal;
		 				break;
		 			case 'Вес':
		 				$product['weight'] = $attrVal;
		 				break;
		 			case 'Размеры упаковки (ШхВхГ)':
		 				$product['sizePack'] = $attrVal;
		 				break;
		 		}    		
	  		}  		
	  	}	 
		$product['imgFile'] = $product['id'].".jpg";
		$product['imgUrl'] = "http://www.riolis.ru/zoom/photos/".$product['id'].".jpg";
	  	return $product;
	}


	function parseCatalog($url){
		$html = file_get_html($url);

		foreach($html->find('.catalog_main') as $div){
			foreach($div->find('.name') as $div2){
				foreach($div2->find('a') as $a){
					file_put_contents('php://stderr', $a->href."\n",FILE_APPEND);
		 			$product = $this->parseSet($a->href);
					print_r($product);
					//if ($id1C = $this->get1CFromSku($product)){
						//$product['id1C'] = $id1C;
						//echo "id1C == ".$id1C."name == ".$product['name']."\n";
		 				//print_r($product);
						$this->saveProductKit($product);	
					// }else{
					// 	echo "!!! Error: sku == ".$product['sku']." name == ".$product['name']." no found Id1c \n";
					// }
					sleep(1);
		 		}
		 	}
		}
	}    


	public function correctSku($skuOriginal){
		// для product-kit
		echo "skuOroginal: ".$skuOriginal."\n";
		if (preg_match('/^([а-яёА-Я]{1,4})(\d+)\/(\d+)\s*/', $skuOriginal, $matches)){
			echo "!!! 1\n";
			$sku = $matches[1].'-'.$matches[2].'-'.$matches[3];	
		}elseif (preg_match('/^([а-яёА-Я]{1,4})-(\d+)\s*/', $skuOriginal, $matches)) {
			echo "!!! 2\n";			
			$sku = $matches[1].'-'.$matches[2];							
		}elseif (preg_match('/^([а-яёА-Я]{1,4})(\d+)\s*/', $skuOriginal, $matches)) {
			echo "!!! 3\n";			
			$sku = $matches[1].'-'.$matches[2];			
		}elseif (preg_match('/^(\d+)([а-яёА-Я]{1,4})\s*/', $skuOriginal, $matches)){
			echo "!!! 4\n";			
			$sku = $matches[2].'-'.$matches[1];
		}elseif (preg_match('/^(\d+)\s+([а-яёА-Я]{1,4})\s*/', $skuOriginal, $matches)){
			echo "!!! 5\n";			
			$sku = $matches[2].'-'.$matches[1];	
		}elseif (preg_match('/^([а-яёА-Я]{1,4})\s+(\d+)\s*/', $skuOriginal, $matches)){
			echo "!!! 6\n";			
			$sku = $matches[1].'-'.$matches[2];
		}elseif (preg_match('/^(\d+)\/(\d+)\s*/', $skuOriginal, $matches)){
			echo "!!! 7\n";			
			$sku = $matches[1].'-'.$matches[2];			
		}elseif (preg_match('/^\s*(\d+)\s+/', $skuOriginal, $matches)){
			echo "!!! 8\n";			
			$sku = $matches[1];																
		}else{
			$sku = $skuOriginal;
		}
		echo "sku: ".$sku."\n";
		return $sku;
	}


	public function getSku(){
		$kolEmpty = 0;
		$kolOk = 0;
		foreach (Product::find()->where(['idCategory' => $this->idCategory])->all() as $product){
			$sku = [];
			$name = $product->nameFull;
			if (preg_match('/^([а-яёА-Я]+)-(\d+)/', $name, $matches)) {
				$sku['type'] = self::SKU_CHAR_NUMBER;
				$sku['char'] = $matches[1];
				$sku['number'] = $matches[2]; 
			}elseif(preg_match('/^(\d+)\/(\d+)/', $name, $matches)){
				$sku['type'] = self::SKU_NUMBER_NUMBER;				
				$sku['number1'] = $matches[1];
				$sku['number2'] = $matches[2];
			}elseif(preg_match('/^(\d+)\s+([а-яёА-Яa-zA-Z]+)\s+/', $name, $matches)){
				//echo "len: ".strlen($matches[2])."\n";
				if (strlen($matches[2]) <= 4){
					$sku['type'] = self::SKU_CHAR_NUMBER;				
					$sku['number'] = $matches[1];
					$sku['char'] = $matches[2];					
				}elseif(preg_match('/^(\d+)/', $name, $matches)){
					$sku['type'] = self::SKU_NUMBER;				
					$sku['number'] = $matches[1];												
				}				
			}elseif(preg_match('/^(\d+)/', $name, $matches)){
				$sku['type'] = self::SKU_NUMBER;				
				$sku['number'] = $matches[1];												
			}				
			// echo $product->id1C." / ".$name."\n";
			// print_r($sku);
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
		//echo "kolEmpty == ".$kolEmpty." koOk == ".$kolOk."\n";
	}


	public function rightProductKitSku(){
		foreach (ProductKit::find()->all() as $productKit) {
			// $productKit->skuOriginal = $productKit->sku;
			// $productKit->save();
			// echo $productKit->skuOriginal." -> ".$productKit->sku."\n";
			echo "skuOriginal: ".$productKit->skuOriginal."\n";
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
				echo "SKU_NUMBER_NUMBER: ".$sku."\n";
			}elseif (preg_match('/(\d+)\s*/', $productKit->skuOriginal, $matches)){
				$sku = $matches[1];
			}else{
				$sku = "???".$productKit->skuOriginal;
			}
			$productKit->sku = $sku;
			echo "sku: ".$sku."\n";
			$productKit->save();
			//echo $productKit->skuOriginal." / "."sku == ".$sku."\n";	
		}
	}


	public function rightProductSku(){
		foreach (Product::find()->where(['idCategory' => $this->idCategory])->all() as $product) {
			// $productKit->skuOriginal = $productKit->sku;
			// $productKit->save();
			echo $product->sku." -> ".$product->name."\n";

			$name = $product->nameFull;
			if (preg_match('/^([а-яёА-Я]{1,4})(\d+)\/(\d+)\s*/', $name, $matches)){
				$sku = $matches[1].'-'.$matches[2].'-'.$matches[3];	
			}elseif (preg_match('/^([а-яёА-Я]{1,4})-(\d+)\s+/', $name, $matches)) {
				$sku = $matches[1].'-'.$matches[2];							
			}elseif (preg_match('/^([а-яёА-Я]{1,4})(\d+)\s+/', $name, $matches)) {
				$sku = $matches[1].'-'.$matches[2];
			}elseif (preg_match('/^(\d+)([а-яёА-Я]{1,4})\s+/', $name, $matches)){
				$sku = $matches[2].'-'.$matches[1];
			}elseif (preg_match('/^(\d+)\s+([а-яёА-Я]{1,4})\s+/', $name, $matches)){
				$sku = $matches[2].'-'.$matches[1];	
			}elseif (preg_match('/^([а-яёА-Я]{1,4})\s+(\d+)\s+/', $name, $matches)){
				$sku = $matches[1].'-'.$matches[2];
			}elseif (preg_match('/^(\d+)\/(\d+)\s+/', $name, $matches)){
				$sku = $matches[1].'-'.$matches[2];			
			}elseif (preg_match('/^\s*(\d+)\s+/', $name, $matches)){
				$sku = $matches[1];																
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
					//echo "link ok: ".$product->name." / ".$productKit->name."\n"; 
					$product->description = $productKit->description;
					$product->thread = $productKit->thread;
					$product->size = $productKit->size;
					$product->sizePack = $productKit->sizePack;
					$product->canva = $productKit->canva;
					$product->author = $productKit->author;
					$product->language = "рус., анг., нем., фран., исп., итал."; //$productKit->language;
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
			// $this->parseCatalog('http://www.riolis.ru/catalog/nabory_dlya_vyshivki_krestikom.html');
			// for ($i=2; $i <= 38; $i++) { 
			// 	$this->parseCatalog('http://www.riolis.ru/catalog/6_'.$i.'.html');
			// 	sleep(1);				
			// }    	
					   
			// $this->parseCatalog('http://www.riolis.ru/catalog/tovary_dlya_rukodeliya.html');
			// for ($i=2; $i <= 6; $i++) { 
			// 	$this->parseCatalog('http://www.riolis.ru/catalog/103_'.$i.'.html');
			// 	sleep(1);				
			// }	
				
			// for ($i=1; $i <= 3; $i++) { 
			// 	$this->parseCatalog('http://www.riolis.ru/catalog/96_'.$i.'.html');
			// 	sleep(1);				
			// }		 
			
			// for ($i=1; $i <= 25; $i++) { 
			// 	$this->parseCatalog('http://www.riolis.ru/catalog/98_'.$i.'.html');
			// 	sleep(0.5);	
			// }		

			// http://www.riolis.ru/catalog/89_1.html
			for ($i=1; $i <= 3; $i++) { 
				$this->parseCatalog('http://www.riolis.ru/catalog/89_'.$i.'.html');
				sleep(0.5);	
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

