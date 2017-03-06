<?php

namespace app\modules\exportoc\models;


use Yii;

/**
 * This is the model class for table "oc_manufacturer_to_store".
 *
 * @property integer $manufacturer_id
 * @property integer $store_id
 */
class OcManufacturerToStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_manufacturer_to_store';
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
            [['manufacturer_id', 'store_id'], 'required'],
            [['manufacturer_id', 'store_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'manufacturer_id' => 'Manufacturer ID',
            'store_id' => 'Store ID',
        ];
    }
}
