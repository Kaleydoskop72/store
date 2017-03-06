<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $idParent
 * @property string $name
 * @property string $description
 * @property integer $isImage
 */
class Category extends \yii\db\ActiveRecord
{
    const TYPE_NONE     = 1;  
    const TYPE_FABRIC   = 2;  
    const TYPE_YARN     = 3;
    const TYPE_KIT      = 4;
    const TYPE_BAGUETTE = 5;      
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }


    public function rules()
    {
        return [
            [['name'], 'required'],
            [['idParent', 'isImage', 'typeProduct'], 'integer'],
            [['isWork', 'isProduct', 'idProduct', 'isReady'], 'integer'],
            [['description', 'title'], 'string'],
            [['name'], 'string', 'max' => 255],        
        ];
    }

                // foreach (Product::find()->where(['idCategory' => $catId, 'isReady' => 1, 'idParent' => 0])->all() as $p){
                //     $kolProducts += Product::find()->where(['idParent' => $p->id, 'isReady' => 1])->count(); 
                // }

    public function getCount(){
        $aChilds = [ $this->id ];
        $kolProducts = 0;
        foreach (Category::find()->where(['idParent' => $this->id, 'isReady' => 1])->all() as $cats){
            array_push($aChilds, $cats->id);
            foreach (Category::find()->where(['idParent' => $cats->id, 'isReady' => 1])->all() as $cats2){
                array_push($aChilds, $cats2->id);
            }            
        }
        foreach ($aChilds as $catId){
            $cat = Category::findOne($catId);
            if ($cat->typeProduct == Category::TYPE_YARN){
                if ($cat->isProduct){
                    $kol = Product::find()->where(['idParent' => $cat->idProduct, 'isReady' => 1])->count(); 
                    $kolProducts += $kol;   
                }
            }else{
                $kol = Product::find()->where(['idCategory' => $catId, 'idParent' => 0, 'isReady' => 1])->count(); 
                $kolProducts += $kol;   
            }
        }
        return $kolProducts;
    }


    public static function getListType(){
        $l = [ 
            self::TYPE_NONE     => '---',        
            self::TYPE_YARN     => 'Пряжа',
            self::TYPE_KIT      => 'Набор для вышивания',
            self::TYPE_FABRIC   => 'Ткань',      
            self::TYPE_BAGUETTE => 'Багет',                               
         ];
         return $l;
    }


    public static function getNameType($tp){
        if ($tp < 1 || $tp > self::TYPE_BAGUETTE){
            $tp = self::TYPE_NONE;
        }
        return self::getListType()[$tp];
    }    


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idParent' => 'Родительская категория',
            'name' => 'Наименование',
            'description' => 'Описание',
            'isImage' => 'Фото',
            'typeProduct' => 'Тип продукта',      
            'isProduct' => 'Продукт',  
            'idProduct' => 'id продукта',     
            'isReady' => 'готово',       
        ];
    }

    /**
     * @inheritdoc
     * @return CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }



    public static function getList($isGenRoot = false){
        $listParent = Category::find()->where(['idParent' => 0, 'isWork' => 1])->all(); 
        $list = [];
        if ($isGenRoot){
            $list[''][0] = 'Root';
        }
        foreach ($listParent as $parent){
            $parentId = $parent->id;
            $parentName = $parent->name;
            $list[$parentName][$parentId] = $parentName;
            $listCat = Category::findAll(['idParent' => $parentId]);
            foreach ($listCat as $cat){
                $list[$parentName][$cat->id] = $cat->name;
            }
        }
        return $list;
    }


    public static function getListFilter(){
        $listParent = Category::find()->where(['idParent' => 0, 'isWork' => 1])->all(); 
        $list = [];
        foreach ($listParent as $parent){
            $parentId = $parent->id;
            $parentName = $parent->name;
            $listCat = Category::findAll(['idParent' => $parentId, 'isWork' => 1]);
            $i = 0;
            foreach ($listCat as $cat){
                $list[$cat->id] = (($i == 0)? $parentName."/" : str_pad('', strlen($parentName)+2, '.')).$cat->name;
                $i++;
            }
        }
        return $list;
    }


    public static function getListParentFilter(){
        $listParent = Category::find()->where(['idParent' => 0, 'isWork' => 1])->all(); 
        $list = [];
        foreach ($listParent as $parent){
            $parentId = $parent->id;
            $parentName = $parent->name;
            $listCat = Category::findAll(['idParent' => $parentId, 'isWork' => 1]);
            $i = 0;
            foreach ($listCat as $cat){
                $list[$cat->id] = (($i == 0)? $parentName."/" : str_pad('', strlen($parentName)+2, '.')).$cat->name;
                $i++;
            }
        }
        return $list;
    }


    public function getName($id){
        $name = '';
        if ($id == 0){
            $name = '/';
        }else{
            $c = Category::findOne($id);
            if ($c){
                $name = $c->name;
                if ($c->idParent != 0){
                    $c = Category::findOne($c->idParent);
                    if ($c){
                        $name = $c->name.'/'.$name;
                    }                    
                }
            }
        }
        return $name;
    }


}
