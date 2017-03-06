<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_product_recurring".
 *
 * @property integer $product_id
 * @property integer $recurring_id
 * @property integer $customer_group_id
 */
class OcProductRecurring extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_product_recurring';
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
            [['product_id', 'recurring_id', 'customer_group_id'], 'required'],
            [['product_id', 'recurring_id', 'customer_group_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'recurring_id' => 'Recurring ID',
            'customer_group_id' => 'Customer Group ID',
        ];
    }
}
