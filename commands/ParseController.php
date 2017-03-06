<?php

namespace app\commands;
// use \simple_html_dom;
use yii\console\Controller;
use app\models\Product;
use app\models\ProductKit;

class ParseController extends Controller{

	public $dirImage = '';

	public function getVal($data, $key, $defVal){
		return array_key_exists($key, $data) ? $data[$key] : $defVal;
	}

	public function findKey($str, $aKey, &$productKey, &$productVal){
		//echo "str == $str\n";	
		foreach ($aKey as $key){
			//echo "key == ".$key['key']."\n";
			if (preg_match('/'.$key['key'].':\s+(.+);/', $str, $matches) ||
				preg_match('/'.$key['key'].':\s+(.+)\./', $str, $matches)){
				//echo "   ok!\n";
				$productKey = $key['productKey'];			
				$productVal = $matches[1];
				return 1;
			}
		}
		return 0;
	}


	public function getDom($url){
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_HEADER, false);
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_REFERER, $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
	    $str = curl_exec($curl);
	    curl_close($curl);

	    $dom = new simple_html_dom();   
	    $dom->load($str);  
	    return $dom;
	}	


    public function findId1c($sku){
        foreach ($this->aSku1c as $sku1c) {
            if ($sku1c['sku'] === $sku){
                return $sku1c['id1c'];
            }
        }
        return 0;
    }


    public function saveProductKit($data){
    	if (strlen($data['sku']) == 0){
    		return;
    	}
    	if (!($product = ProductKit::find()->where(['sku' => $data['sku']])->one())){
     		$product = new ProductKit();
     		echo "add product: ".$data['sku']." / ".$data['name']."\n";
    	}
    	$product->sku = $data['sku'];
    	$product->idManufacturer = $data['idManufacturer'];
    	$product->name = $this->getVal($data, 'name', '');	
    	$product->description = $this->getVal($data, 'description', '');
    	$product->price = $this->getVal($data, 'price', '');	
    	$product->thread = $this->getVal($data, 'thread', '');
    	$product->colorCount = $this->getVal($data, 'numColors', '');
    	$product->size = $this->getVal($data, 'size', '');
    	$product->sizePack = $this->getVal($data, 'sizePack', '');
    	$product->canva = $this->getVal($data, 'canva', '');
    	$product->author = $this->getVal($data, 'author', '');
    	$product->floss = $this->getVal($data, 'floss', '');    	
    	$product->needle = $this->getVal($data, 'needle', '');
    	$product->booklet = $this->getVal($data, 'booklet', '');
    	$product->language = $this->getVal($data, 'language', '');    	
    	$product->design = $this->getVal($data, 'design', '');
    	$product->weight = $this->getVal($data, 'weight', '');    	    	    	    	
		if (!$product->save()){
			echo "ProductKit not save\n";
			print_r($product->getErrors());
		}
		$this->saveImageProductManufacturer($product->id, $data['imgUrl'], $data['imgFile'], $product, $data['idManufacturer']);
		return $product->id;    	
    }



	public function insertProduct($data){
		$product =  new Product();
		$product->idCategory =  $this->getVal($data, 'idCategory', 0);
		$product->name = 		$this->getVal($data, 'name', '');
		$product->nameFull = 	$this->getVal($data, 'nameFull', '');		
		$product->description = $this->getVal($data, 'description', '');
		$product->isImageMain = $this->getVal($data, 'isImageMain', 0);
		$product->isImageMore = $this->getVal($data, 'isImageMore', 0);
		$product->isImageMore2 = $this->getVal($data, 'isImageMore2', 0);		
		$product->colorCode = 	$this->getVal($data, 'colorCode', '');
		$product->colorName = 	$this->getVal($data, 'colorName', '');			
		$product->id1C 	= 		$this->getVal($data, 'id1C', 0);
		$product->idManufacturer = $this->getVal($data, 'idManufacturer', 0);
		$product->structure = 	$this->getVal($data, 'structure', '');
		$product->tech = 		$this->getVal($data, 'tech', '');		
		$product->season = 		$this->getVal($data, 'season', '');
		$product->purpose = 	$this->getVal($data, 'purpose', '');
		$product->weight = 		$this->getVal($data, 'weight', 0);
		$product->weight_unit = $this->getVal($data, 'weight_unit', 0);		
		$product->width = 		$this->getVal($data, 'width', 0);
		$product->length = 		$this->getVal($data, 'length', 0);
		$product->length_unit = $this->getVal($data, 'length_unit', 0);		
		$product->sku = 		$this->getVal($data, 'sku', '');
		$product->price = 		$this->getVal($data, 'price', 0);
		$product->urlSrc = 		$this->getVal($data, 'urlSrc', '');
		$product->design = 		$this->getVal($data, 'design', '');
		$product->comment = 	$this->getVal($data, 'comment', '');
		$product->idLocate = 	$this->getVal($data, 'idLocate', 0);
		$product->isReady = 	$this->getVal($data, 'isReady', 0);		
		$product->isPublished = $this->getVal($data, 'isPublished', 0);
		$product->idParent	 = 	$this->getVal($data, 'idParent', 0);
		$product->rack 	= 		$this->getVal($data, 'rack', '');
		$product->dttm_create = $this->getVal($data, 'dttm_create', date("Y-m-d H:i:s"));	
		$product->dttm_modify = $this->getVal($data, 'dttm_modify', date("Y-m-d H:i:s"));	
		$product->floss 	= 	$this->getVal($data, 'floss', '');		
		$product->canva 	= 	$this->getVal($data, 'canva', '');
		$product->needle 	= 	$this->getVal($data, 'needle', '');
		$product->language 	= 	$this->getVal($data, 'language', '');		
		$product->colorCount 	= 	$this->getVal($data, 'colorCount', 0);
		$product->content 	= 	$this->getVal($data, 'content', 0);		
		$product->size 	= 		$this->getVal($data, 'size', '');			
		if (!$product->save()){
			echo "not save\n";
			print_r($product->getErrors());
		}
		$this->saveImageProductManufacturer($product->id, $data['imgUrl'], $data['imgFile'], $product, $this->idManufacturer);
		return $product->id;
	}


	public function saveProduct($productData){
		if ($product = Product::find()->where(['id1C' => $productData['id1C']])->one()){
			$product->name 		= $productData['name'];
			$product->thread 	= $this->getVal($productData, 'thread', '');
			$product->colorCount = $this->getVal($productData, 'numColors', 0);
			$product->needle 	= $this->getVal($productData, 'needle', '');
			$product->size 		= $this->getVal($productData, 'size', '');
			$product->canva 	= $this->getVal($productData, 'canva', '');
			$product->booklet 	= $this->getVal($productData, 'booklet', '');
			$product->author 	= $this->getVal($productData, 'author', '');
			$product->language 	= $this->getVal($productData, 'language', '');
			$product->weight 	= $this->getVal($productData, 'weight', '');
			$product->sizePack 	= $this->getVal($productData, 'sizePack', '');		
			if (!$product->save()){
				echo "not save: ".$product->id1C." / ".$product->name."\n";
				print_r($product->getErrors());
			}
			$this->saveImageProduct($product->id, $productData['imgUrl'], $productData['imgFile'], $product);
		}else{
			echo "Error: 1c == ".$productData['id1C']. "no found\n";
		}
	}


	public function fetchImage($url, $filename){
	    if (file_exists($filename)){	
	    	echo "unlink '".$filename."' 2>/dev/null\n";
	    	unlink($filename);		
		}			
		echo "wget '".$url."' 2>/dev/null\n";
		system("wget '".$url."' 2>/dev/null");
	    if (!file_exists($filename)){			
			system("wget '".$url."' 2>/dev/null");
		}
		return file_exists($filename);				
	}


	function saveImageProductManufacturer($id, $url, $filename, $product, $idManufacturer){
		$this->dirImage = \Yii::$app->params['dirImageManufacturer'];
		$dir = $this->dirImage."/m".$idManufacturer."/p".$id;
		if (!empty($url)){
			//echo "url == $url\n";
			//echo "dir == $dir\n";
		    if (!file_exists($dir)){
		    	echo $dir."\n";
				mkdir($dir);
			}
			echo "feetch image: ".$url." ".$filename."\n";
			if ($this->fetchImage($url, $filename)){
				$cmd = "mv './".$filename."' ".$dir."/0.jpg";
				echo $cmd."\n";
				system($cmd);
				// $product->isImageMain = 1;
			}
			//$product->save();	
		}
	}    


	public function cpImageKit($product, $productKit){
		$dirImage = \Yii::$app->params['dirImage'];
		$dirImageKit = \Yii::$app->params['dirImageManufacturer'];
		$fileSrc = $dirImageKit."/m".$productKit->idManufacturer."/p".$productKit->id."/0.jpg";
		$dirDst = $dirImage."/p".$product->id."/";
		$fileDst = $dirDst."/0.jpg";
	    if (!file_exists($dirDst)){
			mkdir($dirDst);
		}		
		if (file_exists($fileSrc)){
			$cmd = "cp ".$fileSrc." ".$fileDst;
			//echo $cmd."\n";
			system($cmd);
			$product->isImageMain = 1;
			$product->save();			
		}else{
			echo "!!! *** ### "."cp ".$fileSrc." ".$fileDst."\n";
		}
	}


}



