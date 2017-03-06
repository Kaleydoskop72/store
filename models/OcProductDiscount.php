<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "oc_product_discount".
 *
 * @property integer $product_discount_id
 * @property integer $product_id
 * @property integer $customer_group_id
 * @property integer $quantity
 * @property integer $priority
 * @property string $price
 * @property string $date_start
 * @property string $date_end
 */
class OcProductDiscount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_product_discount';
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
            [['product_id', 'customer_group_id'], 'required'],
            [['product_id', 'customer_group_id', 'quantity', 'priority'], 'integer'],
            [['price'], 'number'],
            [['date_start', 'date_end'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_discount_id' => 'Product Discount ID',
            'product_id' => 'Product ID',
            'customer_group_id' => 'Customer Group ID',
            'quantity' => 'Quantity',
            'priority' => 'Priority',
            'price' => 'Price',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
        ];
    }
}
