<?php


namespace app\commands;
use yii\console\Controller;
use app\models\ProductColor;

include 'simple_html_dom.php';

$listProduct = array();


class PsBusinaController extends ParseController{
    public $categoryId = 13;
    public $manufacturerId = 6;
    public $listColors = [];
    public $listProduct = [];


    public function actionIndex(){
        $this->parseCatalog('http://www.alisa-collection.ru/?id=224');
        for ($i=2; $i <= 21; $i++){
            $this->parseCatalog('http://www.alisa-collection.ru/?id=224&page224='.$i); 
        }
    }



}


$www = 'http://busina.ru';
$aSku = [
	[	'priceSet' => NULL,
		'priceAdd' => 100,
		'category_id' => 74,
		'number' => [ 205, 231, 245, 219, 249, 229, 247, 228, 246, 145, 204, 212, 222, 223, 201, 248, 235, 221, 250, 202, 203 ]],
];

function checkSku($product, $aSku){
	preg_match('/[а-яёА-Я]+-(\d+)/', $product['sku'], $matches);
	$numSku = $matches[1];
	return in_array($numSku, $aSku);
}


function save($product){
	global $db_host, $db_user, $db_pass, $db_name, $db_link;

	//if (checkSku($product)){
		echo "SAVE product: ".$product['name']."\n";
		//print_r($product);
		$link = mysql_connect($db_host, $db_user, $db_pass);
		if (!$link) {
		    die('Ошибка соединения: ' . mysql_error());
		}
		mysql_select_db($db_name);
		mysql_query("SET NAMES utf8 COLLATE utf8_unicode_ci");
		insertProduct($product);
		mysql_close();		
	//}
}


function parseSet($url){
	global $www;
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

	$product['txt'] = '';
	foreach ($html->find('.text') as $div) {
		foreach ($div->find('p') as $p) {
			$product['txt'] =  $product['txt'].' '.trim($p->plaintext);
		}
	}

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


function parseCatalog($url, $category_id, $priceAdd, $aSku){
	global $manufacturer_id;
	$html = file_get_html($url);

	foreach($html->find('.content') as $div){
		foreach ($div->find('.box') as $box) {
			$a = $box->find('a', 0);
			$urlProduct = 'http://busina.ru'.trim($a->href);
			$product = parseSet($urlProduct);
			$product['manufacturer_id'] = $manufacturer_id;
			$product['category_id'] = $category_id;
			$product['price'] = $product['price'] + $priceAdd;
			if (checkSku($product, $aSku)){
				//print_r($product);				
				save($product);
			}
		 } 
	}
}


function parseCatalogAll($url, $category_id, $price){
	global $manufacturer_id;
	$html = file_get_html($url);

	foreach($html->find('.content') as $div){
		foreach ($div->find('.box') as $box) {
			$a = $box->find('a', 0);
			$urlProduct = 'http://busina.ru'.trim($a->href);
			$product = parseSet($urlProduct);
			$product['manufacturer_id'] = $manufacturer_id;
			$product['category_id'] = $category_id;			
			$product['price'] = $price;
			save($product);
		 } 
	}
}



// Иконы
// parseCatalogAll('http://busina.ru/eshop/category/6/', 73, 1400);
// Юв. Бисер Иконы
// parseCatalogAll('http://busina.ru/eshop/category/17/', 80, 1200);
// Цветы
// parseCatalog('http://busina.ru/eshop/category/7/', 74, 100, [ 205, 231, 245, 219, 249, 229, 247, 228, 246, 145, 204, 212, 222, 223, 201, 248, 235, 221, 250, 202, 203 ]);
// Букеты
// parseCatalog('http://busina.ru/eshop/category/8/', 74, 100, [ 205, 231, 245, 219, 249, 229, 247, 228, 246, 145, 204, 212, 222, 223, 201, 248, 235, 221, 250, 202, 203 ]);

// // Пейзажи
// parseCatalog('http://busina.ru/eshop/category/10/', 76, 200, [ 235, 241, 242, 237, 243, 244 ]);
// // Животные
// parseCatalog('http://busina.ru/eshop/category/11/', 75, 100, [ 136, 137, 207, 208, 209 ]);
// // Детские
// parseCatalog('http://busina.ru/eshop/category/23/', 77, 100, [ 504, 507, 505, 508, 506, 509 ]);


// Юв. Бисер Церкви
parseCatalog('http://busina.ru/eshop/category/19/', 80, 100, [ 401, 403, 404 ]);
// Юв. Бисер Бабочки
parseCatalog('http://busina.ru/eshop/category/20/', 80, 100, [ 405, 406, 408 ]);
// Юв. Бисер Цветы
parseCatalog('http://busina.ru/eshop/category/21/', 80, 100, [ 413, 414, 415, 416 ]);




?>