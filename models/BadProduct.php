<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bad_product".
 *
 * @property integer $idProduct
 * @property string $message
 */
class BadProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bad_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idProduct'], 'required'],
            [['idProduct'], 'integer'],
            [['id1C'], 'safe'],            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idProduct' => 'Id Product',
        ];
    }




}
