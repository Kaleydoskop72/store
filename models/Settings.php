<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property integer $id
 * @property integer $idStore
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idStore'], 'required'],
            [['idManufacturerEmpty'], 'integer'],            
            [['idStore', 'isShowCatKit'], 'integer'],
            [['fabricExpire', 'fabricEnd'], 'number'],            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idStore' => 'Склад для ИМ',
            'isShowCatKit' => 'Кат. для наборов',
            'idManufacturerEmpty' => 'пустой производитель',                
            'fabricExpire' => '<= Ткань закончилась',   
            'fabricEnd'    => '<= Ткань остатки',                                 
        ];
    }


    public static function read(&$settings){
        $s = Settings::findOne(1);
        $settings = [
            'idStore' => $s->idStore,
            'isExport2IM' => $s->isExport2IM,
            'isCleanIM' => $s->isCleanIM,
            'isShowCatKit' => $s->isShowCatKit,
            'fabricExpire' => $s->fabricExpire,
            'fabricEnd' => $s->fabricEnd,
            'idManufacturerEmpty' => $s->idManufacturerEmpty,            
        ];        
    }


}
