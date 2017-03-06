<?php

namespace app\modules\exportoc\models;

use app\modules\exportoc\models\OcProduct;
use app\modules\exportoc\models\OcProductSpecial;
use app\modules\exportoc\models\OcProductAttribute;
use app\modules\exportoc\models\OcProductDescription;
use app\modules\exportoc\models\OcProductDiscount;
use app\modules\exportoc\models\OcProductFilter;
use app\modules\exportoc\models\OcProductOption;
use app\modules\exportoc\models\OcProductRecurring;
use app\modules\exportoc\models\OcProductRelated;
use app\modules\exportoc\models\OcProductToCategory;
use app\modules\exportoc\models\OcProductToStore;
use app\modules\exportoc\models\OcProductToLayout;
use app\modules\exportoc\models\OcProductImage;
use app\modules\exportoc\models\OcUrlAlias;
use app\models\Product;
use app\models\ProductStore;
use app\models\Category;
use app\models\Settings;
use app\models\Design;
use app\models\Color;
use app\models\Label;
use app\helpers\MngrLib;

use Yii;

class OcProduct extends OcModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_product';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dboc');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'manufacturer_id'], 'required'],
            [['quantity', 'stock_status_id', 'manufacturer_id', 'shipping', 'points', 'tax_class_id', 'weight_class_id', 'length_class_id', 'subtract', 'minimum', 'sort_order', 'status', 'viewed'], 'safe'],
            [['price', 'weight', 'width', 'height'], 'number'],
            [['date_available', 'date_added', 'date_modified'], 'safe'],
            [['model', 'sku', 'mpn'], 'string', 'max' => 64],
            [['upc'], 'string', 'max' => 12],
            [['ean'], 'string', 'max' => 14],
            [['jan'], 'string', 'max' => 13],
            [['isbn'], 'string', 'max' => 17],
            [['location'], 'string', 'max' => 128],
            [['image'], 'string', 'max' => 255],
        ];
    }


    public function clean(){
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_special')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_related')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_recurring')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_option')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_filter')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_discount')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_attribute')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_description')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_to_store')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_to_category')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_to_layout')->execute(); 
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_image')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_storage')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_filter')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_special')->execute();        
        OcOption::clean();
        OcUrlAlias::clean();
    }    


    public function cleanId($id){
        self::delFromTable('OcProduct',            'product_id', $id);
        self::delFromTable('OcProductImage',       'product_id', $id);        
        self::delFromTable('OcProductSpecial',     'product_id', $id);
        self::delFromTable('OcProductRelated',     'product_id', $id);
        self::delFromTable('OcProductRecurring',   'product_id', $id);                              
        self::delFromTable('OcProductOption',      'product_id', $id);
        self::delFromTable('OcProductFilter',      'product_id', $id);
        self::delFromTable('OcProductDiscount',    'product_id', $id);
        self::delFromTable('OcProductAttribute',   'product_id', $id);                              
        self::delFromTable('OcProductDescription', 'product_id', $id);
        self::delFromTable('OcProductToStore',     'product_id', $id);
        self::delFromTable('OcProductToCategory',  'product_id', $id);
        self::delFromTable('OcProductToLayout',    'product_id', $id);   
        self::delFromTable('OcProductFilter',      'product_id', $id);
        self::delFromTable('OcProductSpecial',     'product_id', $id);        
        OcOption::cleanId($id);
        \Yii::$app->dboc->createCommand()->delete('oc_product_storage', ['product_id' => $id])->execute();
    }


    public function convertIdCompanion($idCompanion){
        $res = '';
        if (strlen($idCompanion) > 0){
            $aId = explode(' ', $idCompanion);
            foreach ($aId as $id1C) {
                if ($p = Product::find()->where(['id1C' => $id1C])->one()){
                    if (strlen($res) > 0){
                        $res .= ' ';
                    }
                    $res .= $p->id;
                }
            }
        }
        return $res;
    }


    public function getSticker($p){
        if ($p->label > 0){
            if ($l = Label::findOne($p->label)){
                return $l->name;
            }
        }
        if (strlen($p->designs) > 0){
            $ddd = $p->designs;
            $aDesign = explode(',', $ddd);
            foreach ($aDesign as $d){
                if ($d = Design::findOne($d)){
                    if ($d->isShow){
                        echo $p->name." / ".$p->designs."\n"; 
                        echo "ok! ".$d->name;
                        return $d->name;
                    }
                }
            }
        }
        return '';
    }



    public function addTblProduct($p){
        //echo "!!!!!!!!!!!!!!!".$p->name."!!!!!!!!!!!!!!!\n";
        $store = [
            'price' => 0,
            'kol'   => 0,
        ];
        foreach (ProductStore::getPriceAvailble($p->id1C, $priceProduct) as $a) {
            if ($a['price'] > 0){
                $store = [
                    'price' => $a['price'],
                    'kol'   => $a['kol'],
                ];
                break;
            }
        }
        if ($p->typeProduct == Category::TYPE_YARN && $store['price'] == 0) {
            //echo "in Category::TYPE_YARN! ".$p->name."\n";
            $store = [ 
                'price' => 0,
                'kol'   => 0,
            ];            
            foreach (Product::find()->where(['idParent' => $p->id])->all() as $pChild) {
                foreach (ProductStore::getPriceAvailble($pChild->id1C, $priceProduct) as $a) {
                    if ($a['kol'] > 0){
                        $store['kol']  += $a['kol'];
                        $store['price'] = $priceProduct;         
                    }
                }
                $priceProduct = $store['price'];
                //echo "kol == ".$store['kol']." price == ".$store['price']."\n";
            }
        }
        //print_r($a);
        $pDst = new OcProduct();
        $pDst->product_id = $p->id;
        $pDst->type_product = $p->typeProduct;
        $pDst->sticker  = self::getSticker($p);
        $pDst->idCompanion = self::convertIdCompanion($p->idCompanion);
        $pDst->id1C = $p->id1C;
        $pDst->sku = $p->sku;
        preg_match('/(\d+)-(\d+)-(\d+)/', $p->dttm_modify, $matches);
        $year  = $matches[1];
        $month = $matches[2];
        $day   = $matches[3];
        $pDst->date_added = $year.'-'.$month.'-'.$day." 00:00:00"; // $p->dttm_modify;
        //echo "date_added: ".$pDst->date_added."\n";
        if (strtotime(date($p->dttm_modify)) < strtotime("-6 month")){
            $pDst->date_added = '0000-00-00 00:00:00';
        }
        $pDst->manufacturer_id  = $p->idManufacturer;        
        $pDst->price  = $priceProduct;
        $pDst->weight = 0; //$p->weight;
        if (empty($pDst->weight)){
            $pDst->weight = 0;
        }
        $pDst->model = '1';     
        $pDst->minimum = 0;     
        $pDst->length = $p->length;
        if (empty($pDst->length)){
            $pDst->length = 0;
        }        
        // $pDst->width  = $p->width;
        $pDst->quantity  = $store['kol'];
        $pDst->stock_status_id  = 7;
        $pDst->manufacturer_id  = $p->idManufacturer;
        $pDst->status = 1;
        if (!$pDst->save()){
            echo "not save OcProduct\n";
            print_r($pDst->getErrors());
        }
        // product_id=48
        $urlAlias = new OcUrlAlias();
        $urlAlias->query = 'product_id='.$p->id;
        $urlAlias->keyword = $p->seoURL;
        $urlAlias->save();
    }


    public function addTblProductSpecial($p, $price){
        $pSpec = new OcProductSpecial();
        $pSpec->product_id = $p->id;
        $pSpec->customer_group_id = 1;
        $pSpec->price = (int) ($price*0.95);
        $pSpec->save();
    }


    public function getProductDescription($p){
        $res = '';
        $typeProduct = $p->getTypeProduct();
        switch ($typeProduct){
            case Category::TYPE_YARN:
                $res = $p->content2;
                if (strlen($res) == 0){
                    $res = $res = $p->content;
                }
                break;
            case Category::TYPE_KIT:
                $res = $p->size;
                break;
            case Category::TYPE_FABRIC:
                $res = $p->content;
                break;
            default:
                $res = $p->description;
        }
        return $res;
    }


    public function addTblProductDescription($p){
        $metaKeyword = "";
        if ($cat = Category::findOne($p->idCategory)) {
            $metaKeyword = "купить ".$cat->name." в Тюмени";
        } 
        $pDst = new OcProductDescription();
        $pDst->product_id = $p->id;
        $pDst->language_id = 1;     
        $pDst->name =   $p->name;
        $pDst->title =  $p->title;
        $pDst->description  = $p->description;
        $pDst->description_catalog = self::getProductDescription($p);
        $pDst->meta_description = $metaKeyword." ".$p->name;  
        $pDst->meta_keyword = $metaKeyword;
        if (!$pDst->save()){
            echo "not save OcProductDescription\n";
            print_r($pDst->getErrors());
        }           
    }


    public function addTblProduct2Category($p){
        $pDst = new OcProductToCategory();
        $pDst->product_id = $p->id;
        $pDst->category_id = $p->idCategory;        
        if (!$pDst->save()){
            echo "not save OcProductToCategory\n";
            print_r($pDst->getErrors());
        }       
    }   


    public function addTblProduct2Layout($p){
        $pDst = new OcProductToLayout();
        $pDst->product_id = $p->id;
        $pDst->store_id = 0;    
        $pDst->layout_id = 0;           
        if (!$pDst->save()){
            echo "not save OcProductToLayout\n";
            print_r($pDst->getErrors());
        }           
    }       


    public function addTblProduct2Store($p){
        $pDst = new OcProductToStore();
        $pDst->product_id = $p->id;
        $pDst->store_id = 0;                
        if (!$pDst->save()){
            echo "not save OcProductToStore\n";
            print_r($pDst->getErrors());
        }           
    }


    public function addProductAttribute($idProduct, $id, $text){
        if (!empty($text)){
            $pa = new OcProductAttribute();
            $pa->attribute_id   = $id;
            $pa->product_id     = $idProduct;
            $pa->text           = $text;
            $pa->language_id    = 1;
            // $pa->save();
            if (!$pa->save()){
                echo "not save OcProductAttribute\n";
                print_r($pa->getErrors());
            }             
        }
    }


    public function addTblProductStorage($p){
        $productType = Category::TYPE_NONE;
        if ($c = Category::findOne($p->idCategory)){
            $productType = $c->typeProduct; 
        }else{
            echo "No Type Product! ".$p->idCategory." / ".$p->id." / ".$p->name."\n";
        }
        $a = ProductStore::getPrices($p->id1C);
        // echo $p->id1C." / ".$p->name."\n";
        // print_r($a);
        $price = 0;
        foreach ($a as $store){
            if ($store['kol'] > 0 && $store['isActive']){
                $isFabricExpire = false;
                $isFabricEnd = false;
                if ($price == 0){
                    $price = $store['price'];
                }
                if ($productType == Category::TYPE_FABRIC){
                    Settings::read($settings);
                    if ($store['kol'] <= $settings['fabricExpire']){
                        $isFabricExpire = true;
                    }
                    if ($store['kol'] <= $settings['fabricEnd']){
                        $isFabricEnd = true;
                    }
                }
                $productStorage = new OcProductStorage();
                $productStorage->product_id = $p->id;
                $productStorage->storage_id = $store['idStore']; 
                $productStorage->kol = $store['kol']; 
                $productStorage->price = $store['price'];
                $productStorage->isFabricExpire = $isFabricExpire;
                $productStorage->isFabricEnd = $isFabricEnd;
                if ($productType == Category::TYPE_FABRIC) {
                    $unit = "м";
                }else{
                    $unit = "шт.";
                }
                //echo $p->id." / ".$p->name." / ".$productType." / ".$unit."\n";
                $productStorage->unit = $unit;
                if (!$productStorage->save()){
                    print_r($productStorage->getErrors());
                }                  
            }
        }
        if ($price > 0){
            self::addTblProductSpecial($p, $price);
        }
    }


    public function addTblProductAttribute($p){
        $cat = Category::find()->where(['id' => $p->idCategory])->one();
        if ($cat){
            $typeProduct = $cat->typeProduct;
            self::addProductAttribute($p->id, OcAttribute::COMMON_COUNTRY, $p->country);
            if (!($p->manufacturer == '---' || $p->manufacturer == '--')){
                self::addProductAttribute($p->id, OcAttribute::COMMON_BRAND, $p->manufacturer); 
            }
            if (!empty($p->designs) || !empty($p->colors)){
                //echo "designs + colors == ".$p->name." ".$typeProduct." ".$p->designs." ".$p->colors."\n";
            }                       
            switch ($typeProduct){
                case Category::TYPE_FABRIC:
                    //self::addProductAttribute($p->id, OcAttribute::FABRIC_DESIGN,  Design::getListTxt($p->id));
                    self::addProductAttribute($p->id, OcAttribute::FABRIC_CONTENT, $p->content);
                    self::addProductAttribute($p->id, OcAttribute::FABRIC_PURPOSE, $p->purpose);
                    self::addProductAttribute($p->id, OcAttribute::FABRIC_COLOR,   $p->colorName); //Color::getListTxt($p->id));                    
                    break;
                case Category::TYPE_KIT:
                    self::addProductAttribute($p->id, OcAttribute::KIT_CONTENT,    $p->content);
                    self::addProductAttribute($p->id, OcAttribute::KIT_TECH,       $p->tech);
                    self::addProductAttribute($p->id, OcAttribute::KIT_CANVA,      $p->canva);
                    self::addProductAttribute($p->id, OcAttribute::KIT_FLOSS,      $p->floss);
                    self::addProductAttribute($p->id, OcAttribute::KIT_SIZE,       $p->size);    
                    self::addProductAttribute($p->id, OcAttribute::KIT_KOL_COLORS, $p->colorCount);                                              
                    break; 
                case Category::TYPE_YARN:
                    self::addProductAttribute($p->id, OcAttribute::YARN_TECH,      $p->tech);
                    self::addProductAttribute($p->id, OcAttribute::YARN_SEASON,    $p->season);
                    //self::addProductAttribute($p->id, OcAttribute::YARN_STRUCTURE, $p->structure);
                    if (strlen($p->content2) > 0){
                        self::addProductAttribute($p->id, OcAttribute::YARN_CONTENT,   $p->content2);
                    }else{
                        self::addProductAttribute($p->id, OcAttribute::YARN_CONTENT,   $p->content);
                    }
                    self::addProductAttribute($p->id, OcAttribute::YARN_LENGTH,    $p->length);
                    self::addProductAttribute($p->id, OcAttribute::YARN_HOOK,      $p->hook);
                    self::addProductAttribute($p->id, OcAttribute::YARN_SPOKES,    $p->spokes); 
                    self::addProductAttribute($p->id, OcAttribute::YARN_WEIGHT,    $p->weight);            
                    self::addProductAttribute($p->id, OcAttribute::YARN_DENSITY,   $p->density_knitting);                                                                           
                    break;                                       
            }                
        }
    }       


    public function addTblProductFilter($p){
        $typeProduct = Category::TYPE_NONE;
        if ($cat = Category::findOne($p->idCategory)){
            $typeProduct = $cat->typeProduct;
        }
        foreach (OcFilter::$filters as $filter) {
            if (in_array($typeProduct, $filter['apply'])){
                if ($filter['type'] == OcFilter::FILTER_DESIGN){
                    foreach (explode(',', $p->designs) as $idDesign){
                        if (!empty($idDesign)){
                            $pf = new OcProductFilter();
                            $pf->product_id = $p->id; 
                            if($ocFilter = OcFilter::find()->where(['filter_group_id' => OcFilter::FILTER_DESIGN, 'mngr_filter_id' => $idDesign])->one()){
                                $pf->filter_id = $ocFilter->filter_id;
                                //echo "idDesign == ".$idDesign."\n";
                                if (!$pf->save()){
                                    echo "OcProductFilter::FILTER_DESIGN\n";
                                    print_r($pf->getErrors());
                                }
                            }                                     
                        }
                    }                   
                }
                if ($filter['type'] == OcFilter::FILTER_COLOR){
                    foreach (explode(',', $p->colors) as $idColor){
                        if (!empty($idColor)){
                            $pf = new OcProductFilter();
                            $pf->product_id = $p->id;
                            if($ocFilter = OcFilter::find()->where(['filter_group_id' => OcFilter::FILTER_COLOR, 'mngr_filter_id' => $idColor])->one()){
                                $pf->filter_id = $ocFilter->filter_id;
                                //echo "idColor == ".$idColor."\n";
                                if (!$pf->save()){
                                    echo "OcProductFilter::FILTER_COLOR\n";
                                    print_r($pf->getErrors());
                                }                                 
                            }                                     
                        }                        
                    }                   
                } 
                if ($filter['type'] == OcFilter::FILTER_CATEGORYKIT){
                    if($ocFilter = OcFilter::find()->where(['filter_group_id' => OcFilter::FILTER_CATEGORYKIT, 'mngr_filter_id' => $p->idKitCategory])->one()){
                        $pf = new OcProductFilter();
                        $pf->product_id = $p->id;
                        $pf->filter_id = $ocFilter->filter_id;
                        //echo "idKitCategory == ".$p->idKitCategory."\n";
                        if (!$pf->save()){
                            echo "OcProductFilter::FILTER_CATEGORYKIT\n";
                            print_r($pf->getErrors());
                        }                                 
                    }                                       
                }                                 
            }
        }
    }




    public function addProductImageN($product, $n){
        if ($ocProduct = OcProduct::find()->where(['product_id' => $product->id])->one()){
            $dirSrc = self::getPathImageSrc($product->id);
            $dirDst = self::getPathImageDst($product->id);
            $ocProduct->photoCheckDir($dirDst);
            $typeProduct = $product->getTypeProduct();
            $isWaterMark = ($typeProduct == Category::TYPE_FABRIC) || ($typeProduct == Category::TYPE_YARN);
            self::copyImage($dirSrc."/".$n.".jpg", $dirDst."/".$n.".jpg", $isWaterMark);
            if ($n == 0){
                $ocProduct->image = self::getPathImageDb($product->id)."/".$n.".jpg";
                $ocProduct->save(); 
            }else{
                $ocProductImage = new OcProductImage();
                $ocProductImage->product_id = $ocProduct->product_id;
                //$ocProductImage->product_image_id = $product->id;
                $ocProductImage->image = self::getPathImageDb($product->id)."/".$n.".jpg";
                if (!$ocProductImage->save()){
                    print_r($ocProductImage->getErrors());
                }
            }
        }
    }


    public function addProductImage($product){
        if ($product->isImageMain){
            self::addProductImageN($product, 0);
        }
        if ($product->isImageMore){
            self::addProductImageN($product, 1);
        }   
        if ($product->isImageMore2){
            self::addProductImageN($product, 2);            
        }           
        if ($product->isImageMore3){
            self::addProductImageN($product, 3);            
        }         
    }


    public static function checkWait(){
        static $state = 0;  // 0 - work, 1 - wait
        static $startTime = 0;
        static $nextTime = 0;
        $currentTime = round(microtime(true) * 1000);
        if ($startTime == 0){
            $startTime = $currentTime;
        }
        if (($currentTime - $startTime) >= 15000){
            return true;
        }
        if ($currentTime > $nextTime){
            $state = ($state == 0) ? 1 : 0;
            $nextTime = $currentTime;
            $nextTime += ($state) ? 1000 : 4500;
            if ($state){
                echo "wait\n";
                sleep(1);
            }
        }
        return false;
    }


    public static function fill(){
        $list = Product::find()->where([ 'idParent' => 0, 'isReady' => 1, 'isPublished' => 0 ])->all();
        MngrLib::printPrc($i = 0, count($list));
        foreach ($list as $p){
            $isHide = true;
            $isUpload = $p->isReady;
            if ($category = Category::findOne($p->idCategory)){
                $isUpload = $isUpload && $category->isReady && $category->isWork;
            }else{
                $isUpload = false;
            }
            self::cleanId($p->id);            
            if ($isUpload){
                MngrLib::printPrc($i++);
                echo "upload: ".$p->id." ".$p->name."\n";
                self::addTblProduct($p);             
                self::addTblProductDescription($p);
                self::addTblProduct2Category($p);
                self::addTblProduct2Layout($p);
                self::addTblProduct2Store($p);
                self::addTblProductAttribute($p);
                self::addProductImage($p);
                self::addTblProductStorage($p);
                self::addTblProductFilter($p);
                $aPriceStore = ProductStore::getPriceAvailble($p->id1C, $price);
                if (count($aPriceStore) > 0){
                    $isHide = false;
                    if (Product::getChildNum($p->id) > 0){
                        $option_id = OcOption::addProduct('цвета '.$p->name);
                        $listChild = Product::find()->where(['idParent' => $p->id])->all();
                        foreach ($listChild as $child){
                            if ($child->isImageMain || $child->isImageMore){
                                $aPriceStore = ProductStore::getPriceAvailble($child->id1C, $price);
                                if (count($aPriceStore) > 0){
                                    OcOption::addProductColor($p, $child, $option_id);
                                }                                
                            }
                        }
                    }
                }
                if (!$p->saveAndPublished()){
                    echo "not save Product\n";
                    print_r($p->getErrors());
                }                      
            }
            if ($isHide){
                if ($ocP = OcProduct::findOne($p->id)){
                    $ocP->status = 0;
                    $ocP->save();
                }                
            }
        } 
        return true;
    }   


    public static function findOff($isFirst=false){    
        if ($isFirst){
            Product::updateAll(['isHandled' => 0]);
        }
        $list = Product::find()->where([ 'idParent' => 0, 'isReady' => 0, 'isHandled' => 0])->limit(4000)->all();
        if (count($list) == 0){
            echo "ok\n";
            return;
        }
        foreach ($list as $p){
            if ($ocP = OcProduct::findOne($p->id)){
                echo "off: ".$p->name."\n";
                $ocP->status = 0;
                if (!$ocP->save(false)){
                    print_r($ocP->getErrors());
                }
            }
            $p->isHandled = 1;
            if (!$p->save(false)){
                echo "No save() Product!\n";
                print_r($p->getErrors());
            }
        }
        foreach (OcProduct::find()->where(['status' => 1])->all() as $OcP){
            $isEnable = false;
            if ($p = Product::findOne($OcP->product_id)){
                if ($p->isReady){
                    $isEnable = true;
                }
            }
            if (!$isEnable){
                echo "off: ".$OcP->product_id."\n";
                $OcP->status = 0;
                if (!$OcP->save(false)){
                    print_r($ocP->getErrors());
                }
            }
        }
    }


    // public function getPathImage(){
    //     return \Yii::$app->params['ocDirImage']."/p".$this->product_id;
    // }

    
    // public function getPathImageSite(){
    //     return "data/product/p".$this->product_id;
    // }


    public function getPathImageSrc($id){
        return \Yii::$app->params['dirImage']."/p".$id;
    }


    public function getPathImageDst($id){
        return \Yii::$app->params['ocDirImage']."/p".$id;
    }    


    public function getPathImageDb($id){
        return "data/product/p".$id;
    }

}
