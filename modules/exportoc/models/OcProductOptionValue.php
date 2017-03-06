<?php

namespace app\modules\exportoc\models;

use Yii;


class OcProductOptionValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_product_option_value';
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
            [['product_option_id', 'product_id', 'option_id', 'option_value_id'], 'required'],
            [['product_option_id', 'product_id', 'option_id', 'option_value_id', 'quantity', 'subtract', 'points'], 'integer'],
            [['price', 'weight'], 'number'],
            [['price_prefix', 'points_prefix', 'weight_prefix'], 'string', 'max' => 1],
        ];
    }

}
