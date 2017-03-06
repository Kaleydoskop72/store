<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_attribute_description".
 *
 * @property integer $attribute_id
 * @property integer $language_id
 * @property string $name
 */
class OcAttributeDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_attribute_description';
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
            [['attribute_id', 'language_id', 'name'], 'required'],
            [['attribute_id', 'language_id'], 'integer'],
            [['name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attribute_id' => 'Attribute ID',
            'language_id' => 'Language ID',
            'name' => 'Name',
        ];
    }
}
