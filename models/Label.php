<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "label".
 *
 * @property integer $id
 * @property string $name
 */
class Label extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'label';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
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
            'name' => 'Метка',
        ];
    }


    public static function getListHtml($idP){
        $product = Product::findOne($idP);
        if (strlen($product->label) > 0){
            if ($label = Label::findOne($product->label)){
                $res = '<label>';
                $res .= $label['name'];
                $res .= '</label>';
            }

        }
        return $res;        
    }    


    public function getList(){
        $list = [];
        foreach (Label::find()->orderBy([ 'id' => SORT_ASC ])->all() as $l){
            array_push($list, [ 'id' => $l->id, 'name' => $l->name ]);
        }
        return $list;
    }


}
