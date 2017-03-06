<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_filter_group".
 *
 * @property integer $filter_group_id
 * @property integer $sort_order
 * @property integer $type_filter
 */
class OcFilterGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_filter_group';
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
            [['sort_order', 'type_filter'], 'required'],
            [['sort_order', 'type_filter'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'filter_group_id' => 'Filter Group ID',
            'sort_order' => 'Sort Order',
            'type_filter' => 'Type Filter',
        ];
    }
}
