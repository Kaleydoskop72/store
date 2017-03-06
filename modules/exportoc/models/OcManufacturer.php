<?php

namespace app\modules\exportoc\models;
// use app\modules\exportoc\models\OcManufacturer;
use app\models\Manufacturer;

use Yii;

/**
 * This is the model class for table "oc_manufacturer".
 *
 * @property integer $manufacturer_id
 * @property string $name
 * @property string $image
 * @property integer $sort_order
 */
class OcManufacturer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_manufacturer';
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
            [['name', 'sort_order'], 'required'],
            [['sort_order'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'manufacturer_id' => 'Manufacturer ID',
            'name' => 'Name',
            'image' => 'Image',
            'sort_order' => 'Sort Order',
        ];
    }


    public function clean(){
        \Yii::$app->dboc->createCommand()->truncateTable('oc_manufacturer')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_manufacturer_to_store')->execute();
    }


    public function fillSimple(){
        self::clean();
        foreach (Manufacturer::find()->all() as $m) {
            $ocM = new OcManufacturer();
            $ocM->manufacturer_id = $m->id;
            $ocM->name = $m->name;
            $ocM->sort_order = 0;
            if (!$ocM->save()){
                print_r($ocM->getErrors());
            }
            $ocMS = new OcManufacturerToStore();
            $ocMS->manufacturer_id = $ocM->manufacturer_id;
            $ocMS->store_id = 0;
            if (!$ocMS->save()){
                print_r($ocMS->getErrors());
            }
        }
    }


    public function fillFast(){
        $fOut = fopen("./manufacturer.csv", "w");
        foreach (Manufacturer::find()->all() as $m) {
            $str  = $m->id.';';
            $str .= $m->name.';';
            echo $str."\n";
            fwrite($fOut, $str);
        }
        fclose($fOut);
    }


    public function fill($mode){
        switch ($mode) {
            case 'exportFast':
                self::fillFast();
                break;
            case 'exportSimple':
                self::fillSimple();
                break;            
        }
    }

}
