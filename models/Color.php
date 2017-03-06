<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "color".
 *
 * @property integer $id
 * @property string $name
 * @property integer $code
 */
class Color extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'color';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['code', 'name'], 'string', 'max' => 255],
            [['isImage', 'sort'], 'integer'],            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Цвет',
            'code' => 'Код цвета',
            'isImage' => 'Иконка',             
            'sort' => 'Сортировка',            
        ];
    }

// <span class="sp-preview"></div>
    public function getDropDownList(){
        $list = [];
        foreach (Color::find()->all() as $c){
            $list[$c->id] = '<b>'.$c->name.'</b>';
        }
        return $list;
    }


    public function getList(){
        $list = [];
        foreach (Color::find()->all() as $c){
            array_push($list, [ 'id' => $c->id, 'code' => $c->code, 'name' => $c->name ]);
        }
        return $list;
    }


    public static function getListTxt($idP){
        $product = Product::findOne($idP);
        $aColors = split(',', $product->colors);
        $res = '';
        foreach (Color::find()->all() as $color){
            if (in_array($color->id, $aColors)){
                if (strlen($res) > 0){
                    $res .= ', ';   
                }
                $res .= $color['name']; 
            }
        }
        return $res;        
    }


    public static function getListHtml($idP){
        $product = Product::findOne($idP);
        $aColors = split(',', $product->colors);
        $res = '';
        foreach (Color::find()->all() as $color){
            if (in_array($color->id, $aColors)){
                $res .= '<label title="'.$color['name'].'">';
                $res .= '<div class="color-box" style="background-color:'.$color['code'].'">';
                $res .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                $res .= '</div>';
                $res .= '</label>';              
            }
        }
        return $res;        
    }

}
