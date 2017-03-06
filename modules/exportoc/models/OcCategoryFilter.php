<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_category_filter".
 *
 * @property integer $category_id
 * @property integer $filter_id
 */
class OcCategoryFilter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_category_filter';
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
            [['category_id', 'filter_id'], 'required'],
            [['category_id', 'filter_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'filter_id' => 'Filter ID',
        ];
    }
}
