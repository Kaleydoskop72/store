<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_attribute_group".
 *
 * @property integer $attribute_group_id
 * @property integer $sort_order
 */
class OcAttributeGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_attribute_group';
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
            [['sort_order'], 'required'],
            [['sort_order'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attribute_group_id' => 'Attribute Group ID',
            'sort_order' => 'Sort Order',
        ];
    }
}
