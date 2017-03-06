<?php

namespace app\models;

use Yii;
use app\models\Settings;

/**
 * This is the model class for table "product_store".
 *
 * @property integer $idProduct
 * @property integer $idStore
 * @property double $price
 * @property integer $kol
 */
class ProductStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idProduct1C', 'idStore', 'price', 'kol'], 'required'],
            [['idProduct1C', 'idStore'], 'integer'],
            [['kol', 'price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idProduct1C' => 'Id Product',
            'idStore' => 'Id Store',
            'price' => 'Price',
            'kol' => 'Kol',
        ];
    }


    public static function orderPrice($a, $b){           
        if ($a['order'] == $b['order']){
            return 0;
        }
        return ($a['order'] < $b['order']) ? -1 : 1;  
    }


    /**
     * Возвращает массив с ценами и количеством
     *
     * @param      <type>  $id1C   The identifier 1 c
     *
     * @return     array   The prices.
     */
    public static function getPrices($id1C){
        $a = (new \yii\db\Query())
            ->select(['idStore', 's.name AS nameStore', 'price', 'kol', 'isActive', 'isMain', 'order' ])
            ->from('product_store ps, store s')
            ->where('ps.idStore=s.id and ps.idProduct1C='.$id1C.' and s.isActive=1')
            ->orderBy('s.order ASC')
            ->all();
        // foreach (ProductStore::find()->where(['idProduct1C' => $id1C])->all() as $store){
        //     if ($s = Store::findOne($store->idStore)){
        //         if ($s->isActive){
        //             array_push($a, ['idStore'  => $store->idStore,
        //                             'nameStore' => $s->name,
        //                             'price'    => $store->price,
        //                             'kol'      => $store->kol,
        //                             'isActive' => $s->isActive,
        //                             'isMain'   => $s->isMain,
        //                             'order'    => $s->order,
        //                             ]);
        //         }
        //     }
        // }
        //uasort($a, 'app\models\ProductStore::orderPrice');
        if ($p = Product::find()->where(['id1C' => $id1C])->one()){
            $typeProduct = $p->getTypeProduct();
            if ($typeProduct == Category::TYPE_KIT || $typeProduct == Category::TYPE_YARN){              
                for ($i=0; $i < count($a); $i++) { 
                    if ($a[$i]['isMain'] == 1){
                        if ($i > 0){
                            $tmp = $a[0];
                            $a[0] = $a[$i];
                            $a[$i] = $tmp;
                        }
                        break;
                    }                    
                }              
            }
        }
        // Yii::info("---".print_r($a, true), 'app'); 
        return $a;
    }


    /**
     * Возвращает массив по наличию товара на активных складах
     *
     * @param      <int>  $id1C   код 1С
     * @param      <int>  $price  цена
     * @return  [ 
     *              'idStore'   => //код склада
     *              'nameStore' => // название склада
     *              'kol'       => // количество на складе
     *          ]
     */
    public static function getPriceAvailble($id1C, &$price){
        // $a = [];
        // foreach (ProductStore::find()->where(['idProduct1C' => $id1C])->all() as $store){
        //     if ($s = Store::find()->where(['id' => $store->idStore, 'isActive' => true])->one()){ 
        //         $a[] =  ['idStore'  => $store->idStore,
        //                         'nameStore' => $s->name,
        //                         'price'    => $store->price,
        //                         'kol'      => $store->kol,
        //                         'isActive' => $s->isActive,
        //                         'order'    => $s->order,
        //                         ];                     
        //     }
        // }
        // uasort($a, 'app\models\ProductStore::orderPrice'); 
        // $price = 0;
        // if (count($a) > 0){
        //     $price = reset($a)['price'];
        // }        
        $a = self::getPrices($id1C);
        if (count($a) > 0){
            $price = reset($a)['price'];
        }           
        return $a;        
    }



    // public static function getStoreIM($id1C){
    //     $store = [
    //         'price' => 0,
    //         'kol' => 0,
    //     ];
    //     $aPrice = ProductStore::getPrices($id1C);
    //     if ($idStore = Settings::findOne(1)->idStore){
    //         foreach ($aPrice as $a){
    //             if ($a['idStore'] == $idStore){
    //                 $store['price'] = $a['price'];
    //                 $store['kol'] = $a['kol'];
    //                 break;
    //             }
    //         }
    //     }
    //     return $store;
    // }


}
