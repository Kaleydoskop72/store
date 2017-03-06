<?php

namespace app\models;

use Yii;

class CountryPro extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    public static function getDb()
    {
        return Yii::$app->get('dbpro0');
    }

}
