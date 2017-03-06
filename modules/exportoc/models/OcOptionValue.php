<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_option_value".
 *
 * @property integer $option_value_id
 * @property integer $option_id
 * @property string $image
 * @property integer $sort_order
 */
class OcOptionValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_option_value';
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
            [['option_id', 'sort_order'], 'required'],
            [['option_id', 'sort_order'], 'integer'],
            [['image'], 'string', 'max' => 255],
        ];
    }

}
