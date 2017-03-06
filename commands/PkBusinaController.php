<?php


namespace app\commands;
use yii\console\Controller;
use app\models\ProductColor;
use app\models\Product;
use app\models\ProductKit;

include 'simple_html_dom.php';


class PkBusinaController extends ParseController{
	const SKU_NONE 	= 0;
	const SKU_CHAR_NUMBER 	= 1;
	const SKU_NUMBER 		= 2;
	const SKU_NUMBER_NUMBER = 3;

	public $idCategory = 3108;
	public $idManufacturer = 14;


	function checkSku($product, $aSku){
		preg_match('/[а-яёА-Я]+-(\d+)/', $product['sku'], $matches);
		$numSku = $matches[1];
		return in_array($numSku, $aSku);
	}


	function parseSet($url){
		$www = 'http://busina.ru';
		$aKey = [
			[ 'productKey' => 'author',  	'key' => 'Дизайнер' ],
			[ 'productKey' => 'size',  		'key' => 'Размер' ],
			[ 'productKey' => 'canva',  	'key' => 'Канва' ],		
			[ 'productKey' => 'numColors',  'key' => 'Количество цветов' ],				
		];
		$html = file_get_html($url);
		$product = array();


		$art = $html->find('.art-box', 0);
		preg_match('/Арт\.\s+([а-яёА-Я]+-\d+)/', $art->plaintext, $matches);			
		$product['sku'] = $matches[1];
		$product['idManufacturer'] = $this->idManufacturer;

		$h1 = $html->find('h1', 0);
		$product['name'] = $h1->plaintext;

		$div = $html->find('.img', 0);
		$img = $div->find('img', 0);
		$product['imgUrl'] = $www.$img->src;
		preg_match('/\/(\w+\.jpg)/', $product['imgUrl'], $matches);	
		$product['imgFile'] = $matches[1];

		$div = $html->find('.cost', 0);
		$patterns = array();
		$patterns[0] = '/&nbsp;/';
		$replacements = array();
		$replacements[0] = '';
		$price = preg_replace($patterns, $replacements, trim($div->plaintext));
		preg_match('/(\d+)\s+.+/', $price, $matches);	
		$product['price'] = $matches[1];

		$product['description'] = '';
		foreach ($html->find('.text') as $div) {
			foreach ($div->find('p') as $p) {
				$product['description'] =  $product['description'].' '.trim($p->plaintext);
			}
		}		
		$aCont = [];
		$i = 0;
		foreach ($html->find('ul.collections-product li') as $li) {
			$aCont[$i++] = trim($li->plaintext);
		}		
		$product['canva'] 	 = $aCont[0];
		$product['language'] = $aCont[1];
		$product['thread'] 	 = $aCont[2];
		$product['needle'] 	 = $aCont[3];
		return $product;
	}


	function findSku($product){
		global $aSku;	
		preg_match('/(.)-(\d+)/', $product['sku'], $matches);
		$skuNum = $matches[2];
		foreach ($aSku as $cat){
			foreach ($cat['number'] as $sku){
				if ($sku == $skuNum){
					return $cat;
				}
			}
		}
		return NULL;
	}


	function parseCatalog($url){
		$html = file_get_html($url);

		foreach($html->find('.content') as $div){
			foreach ($div->find('.box') as $box) {
				$a = $box->find('a', 0);
				$urlProduct = 'http://busina.ru'.trim($a->href);
				$product = $this->parseSet($urlProduct);
				$product['manufacturer_id'] = $this->idManufacturer;
				$product['category_id'] = $this->idCategory;
				$product['price'] = 0;
				print_r($product);					
				$this->saveProductKit($product);
				sleep(1);
			 } 
		}
	}


	// function parseCatalogAll($url, $category_id, $price){
	// 	global $manufacturer_id;
	// 	$html = file_get_html($url);

	// 	foreach($html->find('.content') as $div){
	// 		foreach ($div->find('.box') as $box) {
	// 			$a = $box->find('a', 0);
	// 			$urlProduct = 'http://busina.ru'.trim($a->href);
	// 			$product = $this->parseSet($urlProduct);
	// 			$product['manufacturer_id'] = $manufacturer_id;
	// 			$product['category_id'] = $category_id;			
	// 			$product['price'] = $price;
	// 			//save($product);
	// 		 } 
	// 	}
	// }

	public function handleSku(){
		foreach (Product::find()->where(['idCategory' => $this->idCategory])->all() as $product) {
			echo "> ".$product->name."\n";
			if (!preg_match('/([а-яёА-Я]+)-(\d+)/', $product->name, $matches)){
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


	public function handleLink(){
		$countOk = 0;
		$countError = 0;
		foreach (Product::find()->where(['idCategory' => $this->idCategory])->all() as $product) {
			echo "> ".$product->name."\n";
			if ($pk = ProductKit::find()->where(['sku' => $product['sku']])->one()){
				$pk->id1C = $product->id1C;
				$pk->save();
				$countOk++;
			}else{
				echo "Error: \n\n";
				$countError++;
			}
		}
		echo $countOk.' / '.$countError.'\n';
	}




	public function handleLink2(){
		$countOk = 0;
		$countErr = 0;
		foreach (Product::find()->where(['idCategory' => $this->idCategory])->all() as $product) {
			if (strlen($product->sku) > 0){
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
    	if ($mode == 'parse'){
    		foreach ([17, 19, 20, 21] as $i) {
				$this->parseCatalog('http://busina.ru/eshop/category/'.$i.'/');
    		}
    		foreach ([6, 7, 8, 9, 10, 11, 22, 23] as $i) {
				$this->parseCatalog('http://busina.ru/eshop/category/'.$i.'/');
    		}    		
    	}
    	if ($mode == 'sku'){
    		$this->handleSku();
    	}
    	if ($mode == 'link2'){
    		$this->handleLink2();
    	}    	
    }
}

?>