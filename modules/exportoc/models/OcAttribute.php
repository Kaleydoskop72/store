<?php

namespace app\modules\exportoc\models;

use Yii;

use app\models\Category;
// use app\modules\exportoc\models\OcCategory;

use app\modules\exportoc\models\OcAttributeDescription;
use app\modules\exportoc\models\OcAttributeGroup;
use app\modules\exportoc\models\OcAttributeGroupDescription;

/**
 * This is the model class for table "oc_attribute".
 *
 * @property integer $attribute_id
 * @property integer $attribute_group_id
 * @property integer $sort_order
 */
class OcAttribute extends \yii\db\ActiveRecord
{
    // const AG_KIT     = 0;
    // const AG_FABRIC = 1;
    // const AG_YARN    = 2;
    const COMMON_COUNTRY  = 1;     
    const COMMON_BRAND    = 2; 

    const KIT_KOL_COLORS  = 10;
    const KIT_TECH        = 11;
    const KIT_CANVA       = 12;   
    const KIT_SIZE        = 13;  
    const KIT_CONTENT     = 14;  
    const KIT_FLOSS       = 15; 

    const FABRIC_DESIGN   = 20;
    const FABRIC_CONTENT  = 21;    
    const FABRIC_PURPOSE  = 22;    
    const FABRIC_COLOR    = 23;   

    const YARN_LENGTH     = 30;
    const YARN_WEIGHT     = 31;  
    const YARN_SPOKES     = 32;
    const YARN_HOOK       = 33;  
    const YARN_DENSITY    = 34;                   
    const YARN_CONTENT    = 35;
    const YARN_STRUCTURE  = 36;
    const YARN_SEASON     = 37;
    const YARN_TECH       = 38;      

    // public static $attributesCommon = [
    //     [
    //         'group_id' => 0,
    //         'group_name' => 'Общее',
    //         'list' => [
    //             self::COMMON_COUNTRY => 'Страна',            
    //             self::COMMON_BRAND   => 'Производитель',
    //         ],
    //     ]
    // ];


    // public static $attributesCommon = [
    //     self::COMMON_COUNTRY => 'Страна',            
    //     self::COMMON_BRAND   => 'Производитель',
    // ];


    public static $attributes = [
        [
            'group_id' => Category::TYPE_KIT,
            'group_name' => 'набор для вышивания',
            'list' => [
                self::KIT_KOL_COLORS => 'Количество цветов', 
                self::KIT_CONTENT => 'Состав',
                self::KIT_TECH  => 'Способ вышивания',     
                self::KIT_CANVA => 'Канва',
                self::KIT_SIZE  => 'Размер', 
                self::KIT_FLOSS  => 'Мулине',                 
            ],
        ],[
            'group_id' => Category::TYPE_FABRIC,
            'group_name' => 'ткани',
            'list' => [
                self::FABRIC_DESIGN  => 'Рисунок',
                self::FABRIC_CONTENT => 'Состав',  
                self::FABRIC_COLOR   => 'Цвет',                        
                self::FABRIC_PURPOSE => 'Применение',                     
            ],
        ],[
            'group_id' => Category::TYPE_YARN,
            'group_name' => 'пряжа',
            'list' => [
                self::YARN_LENGTH => 'Длинна&nbsp;нити&nbsp;(м)',
                self::YARN_CONTENT => 'Состав',       
                self::YARN_STRUCTURE => 'Структура нити',
                self::YARN_SEASON => 'Сезонность',        
                self::YARN_TECH => 'Способ вязания',
                self::YARN_SPOKES => 'Спицы',                
                self::YARN_HOOK => 'Крючок',        
                self::YARN_WEIGHT => 'Вес (г)',
                self::YARN_DENSITY => 'Плотность вязания <br>(10 х 10 см)',                
            ],
        ],[
            'group_id' => Category::TYPE_NONE,
            'group_name' => 'общее',
            'list' => [
                self::COMMON_COUNTRY => 'Производство',            
                self::COMMON_BRAND   => 'Брэнд',
            ],
        ]
    ];  


    public static function tableName()
    {
        return 'oc_attribute';
    }


    public static function getDb()
    {
        return Yii::$app->get('dboc');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_group_id', 'sort_order'], 'required'],
            [['attribute_group_id', 'sort_order'], 'integer'],
        ];
    }


    public function addAttributes($aList, $group_id){        
        foreach ($aList as $id => $name) {
            $mA =  new OcAttribute();   
            $mA->attribute_id = $id;
            $mA->attribute_group_id = $group_id;
            $mA->sort_order = $id;
            if (!$mA->save()){
                print_r($mA->getErrors());
            }            
            $mAD =  new OcAttributeDescription();
            $mAD->attribute_id = $id;
            $mAD->language_id = 1;
            $mAD->name = $name;
            if (!$mAD->save()){
                print_r($mAD->getErrors());
            }               
        }          
    }


    public function fill(){
        self::clean();
        foreach (self::$attributes as $ag) {
            $mAG = new OcAttributeGroup();
            $mAG->attribute_group_id = $ag['group_id'];
            $mAG->sort_order = 0;
            $mAG->save();
            $mAGD = new OcAttributeGroupDescription();
            $mAGD->attribute_group_id = $ag['group_id'];
            $mAGD->language_id = 1;
            $mAGD->name = $ag['group_name'];            
            $mAGD->save();      
            //self::addAttributes(self::$attributesCommon, $ag['group_id']);            
            self::addAttributes($ag['list'], $ag['group_id']);
        }
    }


    public function clean(){
        \Yii::$app->dboc->createCommand()->truncateTable('oc_attribute')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_attribute_description')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_attribute_group')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_attribute_group_description')->execute();
    }

}

