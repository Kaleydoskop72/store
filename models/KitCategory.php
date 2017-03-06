<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kit_category".
 *
 * @property integer $id
 * @property integer $name
 */
class KitCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kit_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Категория',
        ];
    }


    public static function getList(){
        $listCat = KitCategory::find()->All();
        $list = [];
        foreach ($listCat as $cat){
            $list[$cat->id] = $cat->name;
        }
        return $list;
    }


    public static function getListFilter(){
        $list = [];
        foreach (KitCategory::find()->all() as $item){
            $list[$item->id] = $item->name;            
        }
        return $list;
    }

}
