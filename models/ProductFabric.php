<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_fabric".
 *
 * @property integer $id
 * @property string $design
 * @property integer $idProduct
 */
class ProductFabric extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_fabric';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['design', 'idProduct'], 'required'],
            [['design'], 'string'],
            [['idProduct'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'design' => 'Design',
            'idProduct' => 'Id Product',
        ];
    }

    /**
     * @inheritdoc
     * @return ProductFabricQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductFabricQuery(get_called_class());
    }
}
