<?php


namespace app\models;

use Yii;

/**
 * This is the model class for table "sms".
 *
 * @property integer $id
 * @property string $dttmRx
 * @property string $dttmTx
 * @property string $phone
 * @property string $msg
 * @property integer $state
 * @property string $log
 */
class Sms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dttmRx', 'dttmTx', 'phone', 'msg', 'state', 'log'], 'required'],
            [['dttmRx', 'dttmTx'], 'safe'],
            [['state'], 'integer'],
            [['log'], 'string'],
            [['phone', 'msg'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dttmRx' => 'время приема',
            'dttmTx' => 'время отправки',
            'phone' => 'телефон',
            'msg' => 'сообщение',
            'state' => 'статус',
            'log' => 'журнал',
        ];
    }
}
