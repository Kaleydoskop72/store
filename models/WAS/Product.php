<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $idCategory
 * @property string $name
 * @property string $description
 * @property integer $isImage
 * @property integer $id1C
 * @property integer $idManufacturer
 * @property string $structure
 * @property string $season
 * @property string $purpose
 * @property integer $weight
 * @property integer $width
 * @property integer $length
 * @property string $sku
 * @property double $price
 * @property string $urlSrc
 */
class Product extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['idCategory', 'name', 'description', 'isImage', 'id1C', 'idManufacturer', 'structure', 'season', 'purpose', 'weight', 'width', 'length', 'sku', 'price', 'urlSrc'], 'required'],        
            [['idCategory', 'name', 'id1C', 'price'], 'required'],
            [['idCategory', 'isImageMain', 'id1C', 'idManufacturer', 'isPublished', 'isReady' ], 'integer'],
            [['description', 'design'], 'string'],
            [['price'], 'number'],
            [['forFilter', 'techEmbr', 'thread', 'kolInPack', 'weight', 'width', 'length'], 'string', 'max' => 255],            
            [['name', 'structure', 'season', 'purpose', 'sku', 'urlSrc', 'colorName', 'colorCode'], 'string', 'max' => 255],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idCategory' => 'Категория',
            'name' => 'Наименование',
            'description' => 'Описание',
            'design' => 'Дизайн',            
            'isReady' => 'Готово к публикации',
            'isPublished' => 'Опубликовано',            
            'isImageMain' => 'Есть Главное Фото',
            'isImageMore' => 'Есть дополнительное Фото',            
            'id1C' => 'Код 1C',
            'idManufacturer' => 'Производитель',
            'structure' => 'Состав',
            'season' => 'Сезон',
            'purpose' => 'Назначение',
            'weight' => 'Вес',
            'width' => 'Ширина',
            'length' => 'Длинна',
            'sku' => 'Артикул',
            'price' => 'Цена',
            'colorName' => 'Цвет',
            'colorCode' => 'Код цвета',         
            'forFilter' => 'Для фильтрации',  
            'techEmbr' => 'Техника вышивания', 
            'thread' => 'Структура нити',  
            'kolInPack' => 'Количество в упаковке',                                                                  
            'urlSrc' => 'url источника',
        ];
    }

    /**
     * @inheritdoc
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }
}
