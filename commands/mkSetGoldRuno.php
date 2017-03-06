
<?php
include "simple_html_dom.php";
include "mk_lib.php";

$dbg = 1;
if ($dbg){
	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "tpopPs12";
	$db_name = "oc";
}else{
	$db_host = "localhost";
	$db_user = "cb66116_test";
	$db_pass = "Qwerty1234";
	$db_name = "cb66116_test";	
}
$manufacturer_id = 16;
$parent_id = 81;
$aCategory = [];

$aSku = [
	[	'char' => 'А',
		'number' => [ 6 ]],
	[	'char' => 'БР',
		'number' => [ 8, 9, 14, 15, 16 ]],
	[	'char' => 'ВК',
		'number' => [ 10, 12, 16, 18, 19, 20, 21, 24, 25, 26, 29, 30 ]],
	[	'char' => 'ВМ',
		'number' => [ 1, 3, 5, 9, 10, 11, 12, 14, 15, 16, 17, 19, 20, 21, 23, 24, 25, 26, 27 ]],
	[	'char' => 'ВС',
		'number' => [ 5, 6, 8, 10 ]],
	[	'char' => 'ГМ',
		'number' => [ 11, 12, 21, 22, 23, 14, 15, 17, 18 ]],
	[	'char' => 'ГН',
		'number' => [ 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14 ]],
	[	'char' => 'ГТ',
		'number' => [ 20, 21, 26, 27, 38, 39, 40, 41, 35, 37 ]],
	[	'char' => 'ДЖ',
		'number' => [ 13, 18, 19, 23, 26, 27, 28, 32, 33, 34, 35, 36, 37 ]],
	[	'char' => 'ДЛ',
		'number' => [ 22, 24, 26, 27, 28, 29, 30, 32, 33 ]],
	[	'char' => 'З',
		'number' => [ 1, 10, 17, 18, 19, 22, 24, 25, 26, 28, 30, 31, 32, 33, 35, 36, 37, 38, 41, 44 ]],
	[	'char' => 'КИ',
		'number' => [ 2, 3, 4, 5, 6, 7, 8 ]],
	[	'char' => 'ЛП',
		'number' => [ 14, 15, 16, 18, 21, 22, 23, 24, 25, 26, 28, 29, 30, 33, 35, 36, 38, 39, 40, 41, 43, 45, 46 ]],
	[	'char' => 'ЛЦ',
		'number' => [ 23, 25, 26, 27, 28, 31, 32, 34, 35, 37, 39, 41, 42, 43, 45, 46, 48, 49, 50, 51, 52, 53, 54, 55, 60, 61, 62 ]],
	[	'char' => 'МГ',
		'number' => [ 9, 10, 11, 12, 13, 17 ]],
	[	'char' => 'МД',
		'number' => [ 7, 9, 10, 11, 12, 13, 14, 15, 16, 17 ]],
	[	'char' => 'МК',
		'number' => [ 4, 5, 7, 9, 10, 11, 12, 13, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 28, 29, 30, 32, 33, 34, 35 ]],
	[	'char' => 'МЛ',
		'number' => [ 12 ]],
	[	'char' => 'НЛ',
		'number' => [ 15, 17, 18, 19, 25, 27, 28, 29, 30, 32, 33, 36, 42, 43 ]],
	[	'char' => 'ПИ',
		'number' => [ 1, 2, 9, 10 ]],
	[	'char' => 'ПФ',
		'number' => [ 4, 7, 8, 10, 12, 13 ]],
	[	'char' => 'РС',
		'number' => [ 10, 11, 13, 14, 17, 18, 19, 20 ]],
	[	'char' => 'РП',
		'number' => [ 7, 8, 9, 10 ]],
	[	'char' => 'РТ',
		'number' => [ 4, 5, 7, 8, 9, 11, 12, 13, 14, 15, 17, 18, 20, 21, 23, 24, 25, 26, 27, 28, 36, 37, 42, 73, 75, 76 ]],
	[	'char' => 'С',
		'number' => [ 1, 3, 5, 6, 7, 8, 9, 12, 13 ]],
	[	'char' => 'СВ',
		'number' => [ 1, 3, 4, 5, 7, 8, 9, 10, 11, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27 ]],
	[	'char' => 'СЖ',
		'number' => [ 12, 16, 17, 20, 21, 22, 23, 26, 27, 28, 29, 30, 38, 40, 41 ]],
	[	'char' => 'СМ',
		'number' => [ 12, 14, 16, 18, 22 ]],
	[	'char' => 'Ф',
		'number' => [ 6, 17, 19, 20, 21, 22, 23, 27, 28, 29, 30, 31, 32 ]],
	[	'char' => 'ЦК',
		'number' => [ 1, 2, 3, 4 ]],
	[	'char' => 'ЧМ',
		'number' => [ 3, 5, 8, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 37, 38, 39, 40, 41, 42, 44, 45, 46 ]],
	[	'char' => 'ЧС',
		'number' => [ 1 ]],		
];

function checkSku($product){
	global $aSku;
	preg_match('/([а-яёА-Я]+)-(\d+)/', $product['sku'], $matches);
	// echo "sku == ";
	// print_r($matches);
	$skuChar = $matches[1];
	$skuNum = $matches[2];
	foreach ($aSku as $itemSku){
		if ($itemSku['char'] == $skuChar){
			foreach ($itemSku['number'] as $n){
				if ($n == $skuNum){
					return true;
				}
			}
		}
	}
	return false;
}


function save($product){
	global $db_host, $db_user, $db_pass, $db_name, $db_link;

	echo "SAVE product: ".$product['name']."\n";
	insertProduct($product);
}


function parseSet($url){
	$aKey = [
		[ 'productKey' => 'author',  	'key' => 'Дизайнер' ],
		[ 'productKey' => 'size',  		'key' => 'Размер' ],
		[ 'productKey' => 'canva',  	'key' => 'Канва' ],		
		[ 'productKey' => 'numColors',  'key' => 'Количество цветов' ],				
	];
	$html = file_get_html($url);
	$product = array();


	$div = $html->find('.title', 0);
	$h1 = $div->find('h1', 0);
	preg_match('/([а-яёА-Я]+-\d+)\s+(.+)/', $h1->plaintext, $matches);			
	$product['sku'] = $matches[1];
	$product['name'] = $matches[2];

	//$divImg = $html->find('.goodImg', 0);
	//echo "divImg == ".$divImg->plaintext."\n";	
	//$img = $divImg->find('img', 0);
	foreach ($html->find('.mainImg') as $div) {
		foreach ($div->find('img') as $img) {
			$patterns = array();
			$patterns[0] = '/\/\/www/';
			$replacements = array();
			$replacements[0] = 'www';
			$product['imgUrl'] =  preg_replace($patterns, $replacements, $img->src);

			preg_match('/\/(\w+\.jpg\?\d+)/', $img->src, $matches);			
			$product['imgFile'] = $matches[1];		
		}
	}

	foreach ($html->find('.bx_item_description') as $div) {
		$a = explode("\n", $div->plaintext);
		$isTxt = 0;
		$product['structure'] = '';
		foreach ($a as $str){
			$str = trim($str);
			if (strlen($str) == 0){
				$isTxt = 1;
			}else{
				if ($isTxt){
					$product['structure'] .= $str;
				}else{
					if (findKey($str, $aKey, $key, $val)){
						$product[$key] = $val;
					}else{												
						if (preg_match('/Вышивается/', $str, $matches)){
							$product['techEmbr'] = $str;
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
	global $aCategory, $parent_id;
	foreach ($aCategory as $cat){
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
	array_push($aCategory, [
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
	global $manufacturer_id;
	$html = file_get_html($url);

	foreach($html->find('.item') as $div){
		$h4 = $div->find('h4', 0);
		$a = $h4->find('a', 0);
		$product = parseSet('http://www.rukodelie.ru'.$a->href);
		if (checkSku($product)){
			$product['manufacturer_id'] = $manufacturer_id;
			$product['category_id'] = getCategoryId($product);
			// echo "product == ";
			// print_r($product);
			save($product);
		}
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

mysqlOpen($db_host, $db_name, $db_user, $db_pass);
parseCatalog('http://www.rukodelie.ru/catalog/Nabory_dlya_vyshivaniya/nabory_dlya_vyshivaniya_krestom/');
for ($i=2; $i <= 81; $i++) { 
	parseCatalog('http://www.rukodelie.ru/catalog/Nabory_dlya_vyshivaniya/nabory_dlya_vyshivaniya_krestom/?PAGEN_1='.$i);
}
mysqlClose();




?>