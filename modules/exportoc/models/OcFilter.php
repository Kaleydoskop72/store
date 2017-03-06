<?php

namespace app\modules\exportoc\models;

use Yii;
use app\models\Design;
use app\models\Color;
use app\models\Category;
use app\helpers\Filesystem;

/**
 * This is the model class for table "oc_filter".
 *
 * @property integer $filter_id
 * @property integer $filter_group_id
 * @property integer $sort_order
 * @property string $color
 */
class OcFilter extends \yii\db\ActiveRecord
{

    const FILTER_DESIGN  = 1;     
    const FILTER_COLOR   = 2; 
    const FILTER_CATEGORYKIT = 3;     

    public static $filters = [
        [
            'name'  => 'Тематика',
            'model' => 'app\models\Design',
            'type'  => self::FILTER_DESIGN,
            'apply' => [ Category::TYPE_FABRIC ],
        ],[
            'name'  => 'Цвета',
            'model' => 'app\models\Color',
            'type'  => self::FILTER_COLOR,
            'func'  => 'addColor',
            'apply' => [ Category::TYPE_FABRIC, Category::TYPE_YARN ],           
        ],[
            'name'  => 'Тематика наборов',
            'model' => 'app\models\KitCategory',
            'type'  => self::FILTER_CATEGORYKIT,
            'apply' => [ Category::TYPE_KIT ],
        ]        
    ];


    public static function tableName()
    {
        return 'oc_filter';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
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
            [['filter_group_id', 'sort_order'], 'required'],
            [['filter_group_id', 'sort_order'], 'integer'],
            [['color'], 'string', 'max' => 255],
        ];
    }


    public function attributeLabels()
    {
        return [
            'filter_id' => 'Filter ID',
            'filter_group_id' => 'Filter Group ID',
            'sort_order' => 'Sort Order',
            'color' => 'Color',
        ];
    }


    public static function addColor(&$f, $m){
        // $isOcSiteFTP = \Yii::$app->params['isOcSiteFTP'];
        $f->color = $m->code;
        if ($m->isImage){
            $fileSrc = \Yii::$app->params['dirImageColors'].'/'.$m->id.'.png';
            $fileDst = \Yii::$app->params['ocDirImageColors'].'/'.$m->id.'.png';
            Filesystem::copy($fileSrc, $fileDst); 
            $f->image = '/image/data/colors/'.$m->id.'.png';
        }else{
            $f->image = '';
        }
        $f->sort_order = $m->sort;
    }


    public function exportSimple(){
        foreach (self::$filters as $f){
            $fg = new OcFilterGroup();
            $fg->sort_order = 0;
            $fg->type_filter = $f['type'];
            if (!$fg->save()){
                echo "OcFilterGroup\n";
                print_r($fg->getErrors());
            }

            $fgD = new OcFilterGroupDescription();
            $fgD->filter_group_id = $fg->filter_group_id;
            $fgD->language_id = 1;
            $fgD->name = $f['name'];
            if (!$fgD->save()){
                echo "OcFilterGroupDescription\n";
                print_r($fgD->getErrors());
            }

            foreach ($f['model']::find()->all() as $m){
                $fl = new OcFilter();
                $fl->filter_group_id = $fg->filter_group_id;
                $fl->mngr_filter_id = $m->id;
                $fl->sort_order = 0;
                if (!empty($f['func'])){
                    $funName = $f['func'];
                    self::$funName($fl, $m);
                }
                if (!$fl->save()){
                    echo "OcFilter\n";
                    print_r($fl->getErrors());
                }

                $fD = new OcFilterDescription();
                $fD->filter_id = $fl->filter_id;
                $fD->language_id = 1;
                $fD->filter_group_id = $fg->filter_group_id;
                $fD->name = $m->name;
                if (!$fD->save()){
                    echo "OcFilterDescription\n";
                    print_r($fD->getErrors());
                }
            }
        }
    }


    public function exportFast(){
        // $fFG = fopen("./filterGroup.csv", "w");
        // $fF  = fopen("./filter.csv", "w");
        // foreach (self::$filters as $f){
        //     $str  = $f['type'].";";
        //     $str .= $f['name'].";";
        //     fwrite($fFG, $str);
        //     $fg = new OcFilterGroup();
        //     $fg->sort_order = 0;
        //     $fg->type_filter = $f['type'];  
        //     if (!$fg->save()){
        //         echo "OcFilterGroup\n";
        //         print_r($fg->getErrors());
        //     }             

        //     $fgD = new OcFilterGroupDescription();
        //     $fgD->filter_group_id = $fg->filter_group_id;
        //     $fgD->language_id = 1;
        //     $fgD->name = $f['name'];
        //     if (!$fgD->save()){
        //         echo "OcFilterGroupDescription\n";
        //         print_r($fgD->getErrors());
        //     }             

        //     foreach ($f['model']::find()->all() as $m){
        //         $fl = new OcFilter();
        //         $fl->filter_group_id = $fg->filter_group_id;
        //         $fl->mngr_filter_id = $m->id;
        //         $fl->sort_order = 0;
        //         if (!empty($f['func'])){
        //             self::$f['func']($fl, $m);
        //         }
        //         if (!$fl->save()){
        //             echo "OcFilter\n";
        //             print_r($fl->getErrors());
        //         }                 

        //         $fD = new OcFilterDescription();
        //         $fD->filter_id = $fl->filter_id;
        //         $fD->language_id = 1;
        //         $fD->filter_group_id = $fg->filter_group_id;
        //         $fD->name = $m->name;
        //         if (!$fD->save()){
        //             echo "OcFilterDescription\n";
        //             print_r($fD->getErrors());
        //         }                 
        //     }          
        // }
    }


    public function fill($mode){
        switch ($mode) {
            case 'exportSimple':
                self::clean();
                self::exportSimple();
                break;
            case 'exportFill':
                self::clean();
                self::exportFill();
                break;
        }
    }


    public function clean(){
        echo "in clean()\n";
        \Yii::$app->dboc->createCommand()->truncateTable('oc_filter')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_filter_description')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_filter_group')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_filter_group_description')->execute();
    }


}
