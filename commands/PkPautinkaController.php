<?php


namespace app\commands;
use yii\console\Controller;
use app\models\Product;
use app\models\ProductKit;

include 'simple_html_dom.php';

$listProduct = array();


class PkPautinkaController extends ParseController{
	const SKU_NONE 	= 0;
	const SKU_CHAR_NUMBER 	= 1;
	const SKU_NUMBER 		= 2;
	const SKU_NUMBER_NUMBER = 3;

	public $idCategory = 18988;
	public $idManufacturer = 100;

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
		foreach($html->find('.v_price') as $div_price){		
		 	if (preg_match('/(.+)&nbsp;/', $div_price->plaintext, $matches)){
		 		$product['price'] = $matches[1];		
		 	}	    		 			
		}
		$product['idManufacturer'] = $this->idManufacturer;
		foreach($html->find('.detail_descr') as $div_descr){		  	
		 	foreach($div_descr->find('li') as $li){
		 		foreach ($li->find('span') as $span) {
		 			$attrName = $span->plaintext;
		 		}
		 		foreach ($li->find('b') as $b) {
		 			$attrVal = $b->plaintext;
		 		}
				$patterns = array();
				$patterns[0] = '/&nbsp;/';
				$replacements = array();
				$replacements[0] = ' ';
				$attrVal =  preg_replace($patterns, $replacements, $attrVal);
		 		switch (trim($attrName)){
		 			case 'Наименование:':
		 				$product['name'] = $attrVal;
		 				break;
		 			case 'Артикул:':
						$patterns = array();
						$patterns[0] = '/№/';
						$replacements = array();
						$replacements[0] = '';
						$attrVal =  preg_replace($patterns, $replacements, $attrVal);	 				
		 				$product['sku'] =  $attrVal; 				
		 				break;
		 			case 'Каталог:':
		 				$product['numCatalog'] = $attrVal;
		 				break;
		 			case 'Нитки:':
		 				$product['thread'] = $attrVal;
		 				break;
		 			case 'Цветов:':
		 				$product['numColors'] = $attrVal;
		 				break;	 								
		 			case 'Игла:':
		 				$product['needle'] = $attrVal;
		 				break;
		 			case 'Размер:':
		 				$product['size'] = $attrVal;
		 				break;
		 			case 'Канва:':
		 				$product['canva'] = $attrVal;
		 				break;
		 			case 'Буклет:':
		 				$product['booklet'] = $attrVal;
		 				break;
		 			case 'Художник:':
		 				$product['author'] = $attrVal;
		 				break;
		 			case 'Язык:':
		 				$product['language'] = $attrVal;
		 				break;
		 			case 'Вес:':
		 				$product['weight'] = $attrVal;
		 				break;
		 			case 'Размеры упаковки (ШхВхГ):':
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
					//print_r($product);
					//if ($id1C = $this->get1CFromSku($product)){
						//$product['id1C'] = $id1C;
						//echo "id1C == ".$id1C."name == ".$product['name']."\n";
						$this->saveProductKit($product);	
					// }else{
					// 	echo "!!! Error: sku == ".$product['sku']." name == ".$product['name']." no found Id1c \n";
					// }
					sleep(1);
		 		}
		 	}
		}
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




	public function handleFiles($dirImage){
 		$files = scandir($dirImage);
 		foreach ($files as $filename){
 			if (strlen($filename) > 3){
 				echo $filename."\n";
 				if (!preg_match('/([а-яёА-Я]+)(\d+)/', $filename, $matches)){
	 				if (preg_match('/([а-яёА-Я]+)-(\d+)/', $filename, $matches)){
				 		//print_r($matches);		
				 	}	
				}	
			 	if (count($matches) == 3){
			 		$sku = $matches[1]."-".$matches[2];
			 		//$size = $matches[3];
			 		//echo $sku." / ".$matches[3]."\n";
			 		if ($product = Product::find()->where(['sku' => $sku])->one()){
						$this->dirImage = \Yii::$app->params['dirImage'];
						$dir = $this->dirImage."/p".$product->id;
					    if (!file_exists($dir)){
					    	echo $dir."\n";
							mkdir($dir);
						}
						//echo "cp './db/pautinka/".$filename."' ".$dir."/0.jpg \n";
						system("cp '".$dirImage."/".$filename."' ".$dir."/0.jpg");
						$product->isImageMain = 1;
						//$product->size = $size;
						$product->save();			 			
			 		}
			 	}		 	
 			}
 		}		
	}


						

	public function handleSku(){
		foreach (Product::find()->where(['idCategory' => $this->idCategory])->all() as $product) {
			echo "> ".$product->name."\n";
			if (!preg_match('/([а-яёА-Я]+)(\d+)/', $product->name, $matches)){
 				if (preg_match('/([а-яёА-Я]+)-(\d+)/', $product->name, $matches)){
			 				
			 	}	
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



	public function handleAttr($typeProduct){
		foreach (Product::find()->where(['idCategory' => $this->idCategory])->all() as $product) {			
			echo $product->name."\n";
			if (preg_match('/([а-яёА-Я]+)-(\d+)/', $product->sku, $matches)){
				$skuChar = $matches[1];	
				echo $skuChar." ".$product->nameFull."\n";			
		 		$product->booklet = "цветная схема";
		 		$product->language = "подробная инструкция на русском языке";				
				if ($skuChar == 'Б'){
					$product->canva = "лен с нанесенным фотореалистичным рисунком и схемой";
					$product->needle = "игла для бисера";
					$product->content = "чешский бисер";
				}else{
					$product->canva = "";
					$product->needle = "";
					$product->content = "мозаика - 30 цветов, холст с нанесенным рисунком и клеевым слоем, пинцет, треугольный поддончик для страз, подробная инструкция.";
					$product->description = "полная выкладка";
				}

				if (preg_match('/([\d,\.]+\s*\*\s*[\d,\.]+\s+см)\s+(\d+)\s+цвет/', $product->nameFull, $matches)){
					$product->size = $matches[1];
					$product->colorCount = $matches[2];			
				}else{
					echo "!!!Error can't detect size & color: ".$product->nameFull."\n";
					echo "		".$product->nameFull."\n";
				}
		 		$product->save();			 		
		 	}else{
		 		echo "!!!Error cant't detect sku: ".$product->nameFull."\n";
		 	}	
		}
	}


    public function actionIndex($mode){
    	if ($mode == 'filesB'){
    		$this->handleFiles("./db/filesB");
    	}
    	if ($mode == 'filesM'){
    		$this->handleFiles("./db/filesM");
    	}    	
    	if ($mode == 'sku'){
    		$this->handleSku();
    	}    	
    	if ($mode == 'attrB'){
    		$this->handleAttr('B');
    	}       
    	if ($mode == 'attrM'){
    		$this->handleAttr('M');
    	}       	    		
    }    


}


