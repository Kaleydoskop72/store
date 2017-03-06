<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_category_path".
 *
 * @property integer $category_id
 * @property integer $path_id
 * @property integer $level
 */
class OcCategoryPath extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_category_path';
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
            [['category_id', 'path_id', 'level'], 'required'],
            [['category_id', 'path_id', 'level'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'path_id' => 'Path ID',
            'level' => 'Level',
        ];
    }
}
