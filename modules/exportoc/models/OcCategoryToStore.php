<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_category_to_store".
 *
 * @property integer $category_id
 * @property integer $store_id
 */
class OcCategoryToStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_category_to_store';
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
            [['category_id', 'store_id'], 'required'],
            [['category_id', 'store_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'store_id' => 'Store ID',
        ];
    }
}
