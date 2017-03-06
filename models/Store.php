<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "store".
 *
 * @property integer $id
 * @property string $name
 */
class Store extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['isActive'], 'boolean'],
            [['isMain'], 'boolean'],             
            [['name'], 'string', 'max' => 255],
            [['order'], 'integer'],              
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Склад',
            'isActive' => 'Активен',   
            'isMain' => 'Главный',            
            'order' => 'Приоритет',                      
        ];
    }

    public static function getList(){
        $list = [];
        foreach (Store::find()->where(['isActive' => 1])->all() as $s){
            $list[$s->id] = $s->name;
        }
        return $list;
    }

}
