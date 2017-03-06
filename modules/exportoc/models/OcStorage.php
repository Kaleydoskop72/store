<?php

namespace app\modules\exportoc\models;

use Yii;
use app\models\Store;

/**
 * This is the model class for table "oc_storage".
 *
 * @property integer $storage_id
 * @property string $name
 */
class OcStorage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_storage';
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
            'storage_id' => 'Storage ID',
            'name' => 'Name',
        ];
    }


    public function clean(){
        \Yii::$app->dboc->createCommand()->truncateTable('oc_storage')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_product_storage')->execute(); 
    }    


    public function addTblStorage($s){
        $storage = new OcStorage();
        $storage->name = $s->name;
        $storage->save();
        if (!$storage->save()){
            echo "not save OcStorage\n";
            print_r($storage->getErrors());
        }       
    }



    public function fill(){
        self::clean();
        $list = Store::find()->where(['isWork' => 1])->all();
        foreach ($list as $s) {
            $ocStorage = new OcStorage();
            $ocStorage->storage_id = $s->id;
            $ocStorage->name = $s->name;
            if (!$ocStorage->save()){
                echo "not save OcStorage\n";
                print_r($ocStorage->getErrors());
            }                                             
        }
    }

}
