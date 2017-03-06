<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "design".
 *
 * @property string $name
 */
class Design extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'design';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['isShow'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Рисунок',
            'isShow' => 'Показывать на фото',
        ];
    }


    // public function getList(){
    //     $list = [];
    //     foreach (Design::find()->all() as $d){
    //         array_push($list, [ 'id' => $d->id, 'name' => $d->name ]);
    //     }
    //     return $list;
    // }

    public function getDropDownList(){
        $list = [];
        foreach (Design::find()->all() as $d){
            $list[$d->id] = $d->name;
        }
        return $list;
    }


    public function getList(){
        $list = [];
        foreach (Design::find()->all() as $c){
            array_push($list, [ 'id' => $c->id, 'name' => $c->name ]);
        }
        return $list;
    }


    public static function getListTxt($idP){
        $product = Product::findOne($idP);
        $aDesigns = split(',', $product->designs);
        $res = '';
        foreach (Design::find()->all() as $design){
            if (in_array($design->id, $aDesigns)){
                echo "design == ".$design['name']."\n";
                if (strlen($res) > 0){
                    $res .= ', ';
                }
                $res .= $design['name'];                            
            }
        }
        return $res;
    }
    

    public static function getListHtml($idP){
        $product = Product::findOne($idP);
        $aDesigns = split(',', $product->designs);
        $res = '';
        foreach (Design::find()->all() as $design){
            if (in_array($design->id, $aDesigns)){
                if (strlen($res) > 0){
                    $res .= '<br>';
                }
                $res .= '<label>';
                $res .= $design['name'];
                $res .= '</label>';                              
            }
        }
        return $res;        
    }    


}
