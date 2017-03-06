<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "history".
 *
 * @property integer $id
 * @property string $dttm
 * @property integer $idProduct
 * @property integer $idEvent
 */
class History extends \yii\db\ActiveRecord
{

    const EVENT_READY     = 1;
    const EVENT_NOREADY   = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dttm', 'idProduct', 'idEvent'], 'required'],
            [['dttm'], 'safe'],
            [['comment'], 'safe'],            
            [['idProduct', 'idEvent'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dttm' => 'Dttm',
            'idProduct' => 'Id Product',
            'idEvent' => 'Id Event',
        ];
    }
}
