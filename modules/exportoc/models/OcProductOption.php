<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_product_option".
 *
 * @property integer $product_option_id
 * @property integer $product_id
 * @property integer $option_id
 * @property string $value
 * @property integer $required
 */
class OcProductOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_product_option';
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
            [['product_id', 'option_id'], 'required'],
            [['product_id', 'option_id', 'required'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_option_id' => 'Product Option ID',
            'product_id' => 'Product ID',
            'option_id' => 'Option ID',
            'required' => 'Required',
        ];
    }
}
