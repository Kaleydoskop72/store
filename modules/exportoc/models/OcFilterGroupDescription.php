<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_filter_group_description".
 *
 * @property integer $filter_group_id
 * @property integer $language_id
 * @property string $name
 */
class OcFilterGroupDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_filter_group_description';
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
            [['filter_group_id', 'language_id', 'name'], 'required'],
            [['filter_group_id', 'language_id'], 'integer'],
            [['name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'filter_group_id' => 'Filter Group ID',
            'language_id' => 'Language ID',
            'name' => 'Name',
        ];
    }
}
