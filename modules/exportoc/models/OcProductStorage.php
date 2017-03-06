<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_product_storage".
 *
 * @property integer $storage_id
 * @property integer $product_id
 * @property integer $kol
 * @property integer $price
 */
class OcProductStorage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_product_storage';
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
            [['storage_id', 'product_id', 'kol', 'price'], 'required'],
            [['storage_id', 'product_id', 'kol'], 'integer'],
            [['price'], 'number'],            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'storage_id' => 'Storage ID',
            'product_id' => 'Product ID',
            'kol' => 'Kol',
            'price' => 'Price',
        ];
    }
}
