<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_option_value_description".
 *
 * @property integer $option_value_id
 * @property integer $language_id
 * @property integer $option_id
 * @property string $name
 */
class OcOptionValueDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_option_value_description';
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
            [['option_value_id', 'language_id', 'option_id'], 'required'],
            [['option_value_id', 'language_id', 'option_id'], 'integer'],
            [['name'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'option_value_id' => 'Option Value ID',
            'language_id' => 'Language ID',
            'option_id' => 'Option ID',
            'name' => 'Name',
        ];
    }
}
