<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "manufacturer".
 *
 * @property integer $id
 * @property string $name
 * @property integer $idCountry
 */
class Manufacturer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manufacturer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['idCountry'], 'safe'],            
            [['idCountry'], 'integer'],           
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'производитель',
        ];
    }

    /**
     * @inheritdoc
     * @return ManufacturerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ManufacturerQuery(get_called_class());
    }


    public static function getList(){
        $listMan = Manufacturer::find()->All();
        $list = [];
        foreach ($listMan as $man){
            //$country = Country::findOne($man->idCountry);
            $list[$man->id] = $man->name; //." (".$country->name.")";
            //$list[$man->id] = $man->name;
        }
        return $list;
    }

}
