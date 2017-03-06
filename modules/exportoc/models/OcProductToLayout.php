<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_product_to_layout".
 *
 * @property integer $product_id
 * @property integer $store_id
 * @property integer $layout_id
 */
class OcProductToLayout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_product_to_layout';
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
            [['product_id', 'store_id', 'layout_id'], 'required'],
            [['product_id', 'store_id', 'layout_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'store_id' => 'Store ID',
            'layout_id' => 'Layout ID',
        ];
    }
}
