<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Settings;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class AutoForm extends Model
{
    public $importFile = "c:\\tmp\\import_1c.txt";
    public $mode = 1;

    const IMPORT_1C = 1;
    const FIND_READY = 2;
    const CLEAN_IM = 3;    
    const EXPORT_2_IM = 4;

    public function rules()
    {
        return [
            [['mode'], 'required'],
            [['mode'], 'integer'],            
            [['importFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'txt'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'mode' => 'режим работы',
            'importFile' => 'файл выгрузки 1C',
        ];
    }


    public function upload()
    {
        if ($this->validate()) {
            $this->importFile->saveAs('uploads/' . $this->importFile->baseName. '.' . $this->importFile->extension);
            return true;
        } else {
            return false;
        }
    }


    public function import1C(){

    }


    public function ready2IM(){
        $settings = Settings::findOne(1);
        $settings->isExport2IM = 1;
        $settings->save();
    }    


    public function clean_im(){
        $settings = Settings::findOne(1);
        $settings->isCleanIM = 1;
        $settings->save();
    }        


    public function make(){
        switch ($this->mode) {
            case self::IMPORT_1C:
                $this->import1C();
                break;
            case self::FIND_READY:
                Product::findReady();
                break;
            case self::CLEAN_IM:
                $this->clean_im();
                break;                   
            case self::EXPORT_2_IM:
                $this->ready2IM();
                break;                                
        }
    }

}
