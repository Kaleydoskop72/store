<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_image".
 *
 * @property integer $id
 * @property integer $idProduct
 * @property integer $idImage
 */
class ProductImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idProduct', 'idImage'], 'required'],
            [['idProduct', 'idImage'], 'integer'],
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
            'idImage' => 'Id Image',
        ];
    }

    /**
     * @inheritdoc
     * @return ProductImageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductImageQuery(get_called_class());
    }
}
