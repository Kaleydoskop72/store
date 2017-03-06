
<?php
include "simple_html_dom.php";

$dbg = 0;
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
$product_id = 0;	
$category_id = 66;

$aSkipSku = array(
	1087,
	1088,
	1439,
	912,
	916,
	1278,
	279,
	266,
	757,
	792,
	839,
	840,
	983,
	984,
	990,
	1273,
	1274,
	1329,
	1330,
	610,
	611,
	612,
	613,
	417,
	662,
	925,
	303,
	373,
	923,
	1003,
	1233,
	1314,
	1325,
	1326,
	1358,
	1359,
	1459,
	1559,
	479,
	630,
	631,
	920,
	1240,
	992,
	993,
	994,
	1269,
	1200,
	1276,
	1239,
	365,
	928,
);


function checkSkipSku($product){
	global $aSkipSku;
	foreach ($aSkipSku as $a){
		if ($a == $product['sku']){
			return 1;
		}
	}
	return 0;
}    


function save($product){
	global $db_host, $db_user, $db_pass, $db_name;

	if (!checkSkipSku($product)){
		$link = mysql_connect($db_host, $db_user, $db_pass);
		if (!$link) {
		    die('Ошибка соединения: ' . mysql_error());
		}
		mysql_select_db($db_name);

		mysql_query("SET NAMES utf8 COLLATE utf8_unicode_ci");

		insertProduct($product);
		insertProductDescription($product);
		insertProductToStore($product);
		insertProductToCategory($product);
		fetchImage($product);
		mysql_close();
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
	$product['fileImg'] = $product['id'].".jpg";
	$product['urlImg'] = "http://www.riolis.ru/zoom/photos/".$product['id'].".jpg";
  	return $product;
}




function parseCatalog($url){
	$html = file_get_html($url);

	foreach($html->find('.catalog_main') as $div){
		foreach($div->find('.name') as $div2){
			foreach($div2->find('a') as $a){
				file_put_contents('php://stderr', $a->href."\n",FILE_APPEND);
	 			$product = parseSet($a->href);
				//print_r($product);
				save($product);
	 		}
	 	}
	}
}


$product = parseSet('http://www.riolis.ru/catalog/details_3367.html');
print_r($product);
save($product);

// parseCatalog('http://www.riolis.ru/catalog/21_1.html');
// parseCatalog('http://www.riolis.ru/catalog/21_2.html');
// parseCatalog('http://www.riolis.ru/catalog/21_3.html');
// parseCatalog('http://www.riolis.ru/catalog/21_4.html');

// parseCatalog('http://www.riolis.ru/catalog/91_1.html');
// parseCatalog('http://www.riolis.ru/catalog/91_2.html');

// parseCatalog('http://www.riolis.ru/catalog/14_1.html');
// parseCatalog('http://www.riolis.ru/catalog/14_2.html');
// parseCatalog('http://www.riolis.ru/catalog/14_3.html');
// parseCatalog('http://www.riolis.ru/catalog/14_4.html');

// parseCatalog('http://www.riolis.ru/catalog/13_1.html');
// parseCatalog('http://www.riolis.ru/catalog/13_2.html');
// parseCatalog('http://www.riolis.ru/catalog/13_3.html');
// parseCatalog('http://www.riolis.ru/catalog/13_4.html');
// parseCatalog('http://www.riolis.ru/catalog/13_5.html');
// parseCatalog('http://www.riolis.ru/catalog/13_6.html');
// parseCatalog('http://www.riolis.ru/catalog/13_7.html');


// parseCatalog('http://www.riolis.ru/catalog/25_1.html');
// parseCatalog('http://www.riolis.ru/catalog/25_2.html');
// parseCatalog('http://www.riolis.ru/catalog/25_3.html');
// parseCatalog('http://www.riolis.ru/catalog/25_4.html');
// parseCatalog('http://www.riolis.ru/catalog/25_5.html');
// parseCatalog('http://www.riolis.ru/catalog/25_6.html');

// parseCatalog('http://www.riolis.ru/catalog/29_1.html');
// parseCatalog('http://www.riolis.ru/catalog/29_2.html');


// parseCatalog('http://www.riolis.ru/catalog/26_1.html');

?>