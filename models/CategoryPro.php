<?php

namespace app\models;

use Yii;

class CategoryPro extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    public static function getDb()
    {
        return Yii::$app->get('dbpro0');
    }


}
