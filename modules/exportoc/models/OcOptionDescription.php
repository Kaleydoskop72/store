<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_option_description".
 *
 * @property integer $option_id
 * @property integer $language_id
 * @property string $name
 */
class OcOptionDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_option_description';
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
            [['option_id', 'language_id', 'name'], 'required'],
            [['option_id', 'language_id'], 'integer'],
            [['name'], 'string', 'max' => 128],
        ];
    }

}
