<?php

namespace app\modules\exportoc\models;

use Yii;

/**
 * This is the model class for table "oc_url_alias".
 *
 * @property integer $url_alias_id
 * @property string $query
 * @property string $keyword
 */
class OcUrlAlias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oc_url_alias';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dboc');
    }


    public function clean(){
        \Yii::$app->dboc->createCommand()->truncateTable('oc_url_alias')->execute();
    }    


    public function makeArticleUrl(){
        $aUrl = [        
                    [ 'common/home', 'main'   ],
                    [ 'information_id=4', 'about-us'   ],
                    [ 'information_id=6', 'shipping-and-payment'   ],
                    [ 'information_id=8', 'stores'   ],
                    [ 'information_id=9', 'bagetnaja-masterskaja'   ],
                ];
        foreach ($aUrl as $url) {
            $urlAlias = new OcUrlAlias();
            $urlAlias->query   = $url[0];
            $urlAlias->keyword = $url[1];
            $urlAlias->save();
        }
    }


    public function rules()
    {
        return [
            [['query', 'keyword'], 'required'],
            [['query', 'keyword'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'url_alias_id' => 'Url Alias ID',
            'query' => 'Query',
            'keyword' => 'Keyword',
        ];
    }
}
