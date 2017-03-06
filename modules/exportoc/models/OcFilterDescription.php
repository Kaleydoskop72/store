<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_filter_description".
 *
 * @property integer $filter_id
 * @property integer $language_id
 * @property integer $filter_group_id
 * @property string $name
 */
class OcFilterDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_filter_description';
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
            [['filter_id', 'language_id', 'filter_group_id', 'name'], 'required'],
            [['filter_id', 'language_id', 'filter_group_id'], 'integer'],
            [['name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'filter_id' => 'Filter ID',
            'language_id' => 'Language ID',
            'filter_group_id' => 'Filter Group ID',
            'name' => 'Name',
        ];
    }
}
