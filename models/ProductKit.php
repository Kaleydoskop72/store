<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_kit".
 *
 * @property integer $id
 * @property integer $idManufacturer
 * @property integer $id1C
 * @property string $sku
 * @property string $name
 * @property string $description
 * @property double $price
 * @property string $thread
 * @property string $size
 * @property string $sizePack
 * @property string $canva
 * @property string $author
 * @property integer $colorCount
 */
class ProductKit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_kit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idManufacturer', 'sku', 'name'], 'required'],
            [['idManufacturer', 'id1C', 'colorCount'], 'integer'],
            [['name', 'description'], 'string'],
            [['price'], 'number'],
            [['sku', 'thread', 'size', 'sizePack', 'canva', 'author'], 'string', 'max' => 255],
            [['needle', 'booklet', 'design', 'language', 'weight'], 'string', 'max' => 255],            
        ];
    }


    public function checkVal($val){
        return (empty($val)) ? 0 : $val;
    }


    public function save($runValidation = true, $attributeNames = NULL){
        $this->colorCount = $this->checkVal($this->colorCount);
        return parent::save($runValidation, $attributeNames);
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idManufacturer' => 'Id Manufacturer',
            'id1C' => 'Id1 C',
            'sku' => 'Sku',
            'name' => 'Name',
            'description' => 'Description',
            'price' => 'Price',
            'thread' => 'Thread',
            'size' => 'Size',
            'sizePack' => 'Size Pack',
            'canva' => 'Canva',
            'author' => 'Author',
            'colorCount' => 'Color Count',
        ];
    }
}
