<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_color".
 *
 * @property integer $id
 * @property integer $idProduct
 * @property string $sku
 * @property string $colorCode
 * @property string $colorName
 * @property integer $isImage
 * @property integer $idImage
 */
class ProductColor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_color';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idProduct', 'colorName'], 'required'],
            [['idProduct', 'isImageMain', 'id1C', 'price', 'isImageMore', 'idImage'], 'integer'],
            [['sku', 'colorCode', 'colorName', 'location', 'comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idProduct' => 'Id Product',
            'sku' => 'Артикул',
            'colorCode' => 'Код цвета',
            'colorName' => 'Название цвета',
            'price' => 'цена', 
            'id1C' => '1C',      
            'location' => 'дислокация', 
            'comment' => 'комментарий',                                    
        ];
    }

    /**
     * @inheritdoc
     * @return ProductColorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductColorQuery(get_called_class());
    }
}
