<?php

namespace app\models;

use Yii;
use app\models\BadProduct;
use app\models\History;
use app\helpers\MngrLib;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $sku
 * @property integer $id1C
 * @property integer $idCategory
 * @property integer $idManufacturer
 * @property integer $idParent
 * @property integer $idLocate
 * @property string $name
 * @property string $description
 * @property string $rack
 * @property string $colorName
 * @property integer $colorCode
 * @property integer $isImageMain
 * @property integer $isImageMore
 * @property double $price
 * @property string $dttm_create
 * @property string $dttm_modify
 * @property integer $isReady
 * @property integer $isPublished
 * @property string $urlSrc
 * @property string $comment
 * @property string $season
 * @property string $purpose
 * @property integer $weight
 * @property integer $weight_unit
 * @property integer $length
 * @property integer $length_unit
 */
class Product extends \yii\db\ActiveRecord
{

    public $control;

    public function initNew(){              
        if ($this->isNewRecord){
            $this->sku             = '';
            $this->id1C            = 0;
            $this->idCategory      = 0;
            $this->idManufacturer  = 0;
            $this->idParent        = 0;        
            $this->name            = '';
            $this->description     = '';
            $this->rack            = '';
            $this->colorName       = '';
            $this->colorCode       = 0;
            $this->isImageMain     = 0;
            $this->isImageMore     = 0;
            $this->isImageMore2    = 0;            
            $this->price           = 0;
            $this->dttm_create     = date("Y-m-d H:i:s");
            $this->dttm_modify     = date("Y-m-d H:i:s");
            $this->isReady         = 0;
            $this->isPublished     = 0;
            $this->urlSrc          = '';
            $this->comment         = '';
            $this->content         = '';            
            $this->season          = '';
            $this->purpose         = '';
            $this->weight          = 0;
            $this->weight_unit     = 0;
            $this->length          = 0;
            $this->length_unit     = 0;
            $this->tech            = '';
            $this->floss           = '';
            $this->canva           = '';
            $this->needle          = '';
            $this->language        = '';
            $this->colorCount      = 0;       
            $this->size            = '';       
            $this->isNew           = 0;        
        }      
    } 


    public function checkVal($val){
        return (empty($val)) ? 0 : $val;
    }


    public function saveAndPublished(){
        $this->idCategory = $this->checkVal($this->idCategory);
        $this->idManufacturer = $this->checkVal($this->idManufacturer);
        $this->idCountry = $this->checkVal($this->idCountry);
        $this->idParent = $this->checkVal($this->idParent);
        $this->isPublished = 1;
        return parent::save();
    }



    public function save($runValidation = true, $attributeNames = NULL){
        $this->idCategory = $this->checkVal($this->idCategory);
        $this->idManufacturer = $this->checkVal($this->idManufacturer);
        $this->idCountry = $this->checkVal($this->idCountry);
        $this->idParent = $this->checkVal($this->idParent);
        $this->isPublished = 0;
        return parent::save($runValidation, $attributeNames);
    }


    public static function tableName()
    {
        return 'product';
    }

    public function getControl(){
        return Product::checkData($this->id);
    }


    public function getCountry(){
        $res = '';
        if ($c = Country::findOne($this->idCountry)){
            $res = $c->name;
        }
        return $res;
    }    


    public function getManufacturer(){
        $res = '';
        if ($m = Manufacturer::findOne($this->idManufacturer)){
            $res = $m->name;
        }
        return $res;
    }


    public function getTypeProduct(){
        $typeProduct = Category::TYPE_NONE;
        if ($cat = Category::findOne($this->idCategory)){
            $typeProduct = $cat->typeProduct;
        }        
        return $typeProduct;
    }


    public function getTypeProductStr(){
        $aTypeStr = [
            [ 'type' => Category::TYPE_NONE,    'name' => "-" ],
            [ 'type' => Category::TYPE_KIT,     'name' => "Набор для вышивания" ],
            [ 'type' => Category::TYPE_YARN,    'name' => "Пряжа" ],
            [ 'type' => Category::TYPE_FABRIC,  'name' => "Ткань" ],
        ];
        $typeProduct = $this->getTypeProduct();
        foreach ($aTypeStr as $typeStr){
            if ($typeStr['type'] == $typeProduct){
                return $typeStr['name'];
            }
        }
        return '-';
    }
    


    public static function checkData($id, &$errors){
        $errors = [];
        if ($product = Product::findOne($id)){
            $typeProduct = $product->getTypeProduct();
        } 

        $store = ProductStore::getPrices($product->id1C);

        $chkPhoto = $product->isImageMain || $product->isImageMore || $product->isImageMore2;
        $errors['badPhoto'] = !$chkPhoto;

        $chkName = strlen($product->name) > 0;
        $errors['badName'] = !$chkName;

        $chkSku = true; //strlen($product->sku) > 0;
        $errors['badSku'] = !$chkSku;

        $chkId1C = strlen($product->id1C) > 0;   
        $errors['badId1C'] = !$chkId1C;

        $chkCategory = $product->idCategory > 0;   
        $errors['badCategory'] = !$chkCategory;

        $chkManufacturer = true; //$product->idManufacturer > 0;
        $errors['badManufacturer'] = !$chkManufacturer;

        $chkprice = true;
        if (count($store) > 0){
            foreach ($store as $p1) {
                foreach ($store as $p2) {
                    if ($p2['price'] == 0){
                        $chkprice = false;
                        break;
                    }
                    if (($p1['price']/$p2['price']) > 2){
                        $chkprice = false;
                        break;
                    }
                }
            }
        }else{
            $chkprice = false;
        }

        // foreach ($store as $s) {
        //     if ($s['price'] > 0){
        //         $chkprice = true;
        //         break;
        //     }
        // }
        $errors['badPrice'] = !$chkprice;

        $chkAll = true;
        switch ($typeProduct) {
            case Category::TYPE_FABRIC:
                $chkColorName = true; //strlen($product->colorName) > 0;
                $errors['badColorName'] = !$chkColorName;      
                $chkContent = strlen($product->content) > 0;
                $errors['badContent'] = !$chkContent; 
                $chkAll = $chkColorName && $chkContent;                         
                break;
            case Category::TYPE_KIT:
                $chkCatKit = $product->idKitCategory > 0;
                $errors['badCatKit'] = !$chkCatKit; 
                $chkContent = true; //strlen($product->content) > 0;
                $errors['badContent'] = !$chkContent;  
                $chkSize = true; //strlen($product->size) > 0;
                $errors['badSize'] = !$chkSize;  
                $chkColorCount = true; //strlen($product->colorCount) > 0;
                $errors['badColorCount'] = !$chkColorCount;  
                $chkCanva = strlen($product->canva) > 0;
                $errors['badCanva'] = !$chkCanva; 
                $chkTech = true; //strlen($product->tech) > 0;
                $errors['badTech'] = !$chkTech;                 
                $chkAll = $chkCatKit && $chkContent && $chkSize && $chkColorCount && $chkCanva && $chkTech;
                break;
            case Category::TYPE_YARN:                
                if ($product->idParent == 0){
                    $chkChilds = false;
                    foreach (Product::find()->where([ 'idParent' => $product->id ])->all() as $child){
                        $childErrors = [];
                        $chkChild = Product::checkData($child->id, $childErrors);
                        $chkChilds |= $chkChild;
                        $child->isReady = $chkChild;
                        if (!$child->save()){
                            echo "not save!\n";
                            print_r($child->getErrors());
                        }
                    }
                    $errors['chkChilds'] = !$chkChilds;                     
                    $chkAll = $chkChilds;
                }
                // echo "***: ".$chkAll."\n";
                // echo "chkPhoto: ".$chkPhoto."\n";
                // echo "chkName: ".$chkName."\n";
                // echo "chkId1C: ".$chkId1C."\n";
                // echo "chkCategory: ".$chkCategory."\n";
                // echo "chkManufacturer: ".$chkManufacturer."\n";
                // echo "chkprice: ".$chkprice."\n";
                break;
        }
        $chkAll = $chkAll && $chkPhoto && $chkName  && $chkId1C && $chkCategory && $chkManufacturer && $chkprice;        
        return ($chkAll) ? 1 : 0;
    }


    public function getSnapShot(){
        $aFields = [
            'sku', 'id1C', 'idCategory', 'idManufacturer',
            'idParent', 'name', 'description', 'colorName',
            'colorCode', 'isImageMain', 'isImageMore', 'isImageMore2', 'isImageMore3',
            'season', 'purpose', 'weight', 'length', 'width', 'floss', 'canva',
            'needle', 'language', 'colorCount', 'size', 'idKitCategory', 'design',
            'content', 'tech', 'structure', 'colors', 'designs', 'idCountry', 'nameFull',
            'thread', 'sizePack', 'author', 'booklet', 'isNew', 'hook', 'spokes',
            'density_knitting', 'idCompanion', 'content2',
        ];
        $str = '';
        foreach ($aFields as $f) {
            $str .= $this->$f;
        }
        return crc32($str);
    }


    public function findReady($isFirst){
        $iProduct = 0;
        // if ($isFirst){
            \Yii::$app->db->createCommand()->truncateTable('bad_product')->execute();
            // Product::updateAll(['isHandled' => 0]);
        // }
        // $list = Product::find()->where([ 'idParent' => 0, 'isImageMain' => 1, 'isHandled' => 0])->limit(1000)->all();
        // $list = Product::find()->where([ 'idParent' => 0, 'isImageMain' => 1])->all();
        $list = Product::find()->all();       
        $iCollect = 0;
        // if ($isPhoto){
        //     $list = Product::find()->where(['idParent' => 0, 'isImageMain' => 1])->all();
        // }else{
        //     $list = Product::find()->where(['idParent' => 0])->all();
        // }
        MngrLib::printPrc($i = 0, count($list));
        foreach ($list as $p){
            MngrLib::printPrc($i++);
            $snapShot = $p->getSnapShot();
            if ($snapShot == $p->snapShot){
                continue;
            }
            $p->snapShot = $snapShot;
            $isReadyOld = $p->isReady;
            $p->isHandled = 1;
            if ($iProduct++ > 200){
                echo $p->name."\n";
                $iProduct = 0;
            }
            $errors = [];
            $chk = Product::checkData($p->id, $errors);
            if ($p->isReady != $chk && $chk) {
                $p->dttm_modify = date("Y-m-d H:i:s");                                 
            }
            if ($chk != $isReadyOld){
                $h = new History();
                $h->dttm = date("Y-m-d H:i:s");
                $h->idProduct = $p->id;
                $h->idEvent = ($chk) ? History::EVENT_READY : History::EVENT_NOREADY;
                $h->comment = $p->name." ".print_r($errors, true);
                $h->save();
            }
            if (!($p->isReady = $chk)){
                // echo $p->id." / ".$p->id1C." / ".$p->name."\n";
                if ($p->getTypeProduct() == Category::TYPE_YARN){
                    if ($cYarn = Category::find()->where(['idProduct' => $p->id])->one()){
                        $cYarn->isReady = 0;
                        $cYarn->save();
                    }
                }
                $badProduct = new BadProduct();
                $badProduct->idProduct  = $p->id;
                $badProduct->idCategory = $p->idCategory;
                $badProduct->id1C       = $p->id1C;
                $badProduct->badPhoto   = $errors['badPhoto'];
                $badProduct->badName    = $errors['badName'];
                $badProduct->badSku     = $errors['badSku'];
                $badProduct->badId1C    = $errors['badId1C'];                
                $badProduct->badCategory = $errors['badCategory'];
                $badProduct->badManufacturer = $errors['badManufacturer'];
                $badProduct->badPrice       = $errors['badPrice'];  
                if (!empty($errors['badColorName'])){
                    $badProduct->badColorName   = $errors['badColorName'];                     
                }
                if (!empty($errors['badContent'])){
                    $badProduct->badContent     = $errors['badContent'];
                }                                             
                if (!$badProduct->save()){
                    echo "not save badProduct\n";
                    print_r($badProduct->getErrors());
                }
                unset($badProduct);
                if ($iCollect >= 100){
                    gc_collect_cycles();
                }
            }else{
                if ($p->getTypeProduct() == Category::TYPE_YARN){
                    if ($cYarn = Category::find()->where(['idProduct' => $p->id])->one()){
                        $cYarn->isReady = 1;
                        $cYarn->save();
                    }
                }                
            }
            //echo "before save: ".$p->isReady."\n";
            if (!$p->save()){
                print_r($p->getErrors());
            }  
            unset($p);    
        }
    }                


    public function rules()
    {
        return [
            //[['idCategory', 'name',  'price'], 'required'],
            [['idCategory',  'price', 'control', 'weight'], 'safe'],            
            [['id1C', 'idCategory', 'idManufacturer', 'idParent', 'isImageMain', 'isImageMore', 'isImageMore2', 'isReady', 'isArchive', 'isImport', 'weight_unit', 'length_unit', 'isExportMarket'], 'integer'],
            [['description', 'comment', 'name', 'nameFull', 'nameYandex', 'seoURL', 'title'], 'string'],
            [['price'], 'number'],
            [['dttm_create', 'dttm_modify', 'tech' , 'floss', 'canva', 'needle', 'language', 'colorCount', 'idKitCategory', 'length', 'colors', 'idCountry', 'isNew', 'hook', 'spokes', 
                'density_knitting', 'snapShot' ], 'safe'],
            [['sku', 'name', 'rack', 'colorName', 'urlSrc', 'season', 'purpose', 'idCompanion', 'content', 'content2'], 'string', 'max' => 255],
        ];
    }


    public static function getChildNum($id){
        return Product::find()->where(['idParent' => $id, 'isReady' => 1])->count();
    }    


    public function getPathImage(){
        return \Yii::$app->params['dirImage']."/p".$this->id;
    }


    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'sku' => 'Артикул',
            'id1C' => '1C',
            'idCategory' => 'Категория',
            'idManufacturer' => 'Производитель',
            'idParent' => 'Родительский товар',
            'idDesign' => 'Рисунок',            
            'name' => 'Название',
            'description' => 'Описание',
            'rack' => 'Стеллаж',
            'colorName' => 'Название цвета',
            'colorCode' => 'Код цвета',
            'isImageMain' => 'Главное фото',
            'isImageMore' => 'Дополнительное фото',
            'isImageMore2' => 'Дополнительное фото2',            
            'price' => 'Цена',
            'dttm_create' => 'Время создания',
            'dttm_modify' => 'Время модификации',
            'isReady' => 'Готов',
            'isPublished' => 'Опубликован',
            'urlSrc' => 'url исходника',
            'comment' => 'Комментарий',
            'content' => 'Состав из 1C',
            'content2' => 'Состав',
            'season' => 'Сезон',
            'purpose' => 'Назначение',
            'weight' => 'Вес',
            'weight_unit' => 'Вес ед. измерения',
            'length' => 'Длинна',
            'length_unit' => 'Длинна ед. измерения',
            'tech'  => 'Техника вышивания',    
            'floss' => 'Мулине',  
            'canva' => 'Канва',  
            'needle' => 'Игла',              
            'language' => 'Язык инструкции',  
            'colorCount' => 'Количество цветов',     
            'size' => 'Размер',  
            'idKitCategory' => 'Категория для набора', 
            'isNew' => 'Новый товар',     
            'nameYandex' => 'yandex',  
            'seoURL' => 'seoUrl',   
            'hook'   => 'Крючок', 
            'spokes' => 'Спицы',     
            'idCountry' => 'Страна', 
            'density_knitting' => 'Плотность вязания',
            'isArchive' => 'В архиве',
            'isExportMarket' => 'Экспорт в маркет',
            'isImport' => 'Импортирован',
            'idCompanion' => 'Компаньены (код1 код2 код3...)'             
        ];
    }
}
