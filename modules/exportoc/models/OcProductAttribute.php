<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_product_attribute".
 *
 * @property integer $product_id
 * @property integer $attribute_id
 * @property integer $language_id
 * @property string $text
 */
class OcProductAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_product_attribute';
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
            [['product_id', 'attribute_id'], 'required'],
            [['product_id', 'attribute_id', 'language_id'], 'integer'],
        ];
    }

}
