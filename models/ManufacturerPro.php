<?php

namespace app\models;

use Yii;

class ManufacturerPro extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manufacturer';
    }

    public static function getDb()
    {
        return Yii::$app->get('dbpro0');
    }

}
