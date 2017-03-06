<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;

/**
 * ProductSearch represents the model behind the search form about `app\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id1C', 'idCategory', 'idManufacturer', 'idParent', 'colorCode', 'isImageMain', 'isImageMore', 'isImageMore2', 'isExportMarket', 'isReady', 'isArchive', 'weight', 'weight_unit', 'length', 'length_unit', 'idKitCategory', 'control'], 'integer'],
            [['sku', 'name', 'description', 'rack', 'colorName', 'dttm_create', 'dttm_modify', 'urlSrc', 'comment', 'season', 'purpose'], 'safe'],
            [['price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $idParent)
    {
        $query = Product::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // 'pagination' => array(
            //     'pageSize' => 100,
            // ),            
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id1C' => $this->id1C,
            'idCategory' => $this->idCategory,
            'idManufacturer' => $this->idManufacturer,
            'idParent' => $idParent,
            'colorCode' => $this->colorCode,
            'isImageMain' => $this->isImageMain,
            'isExportMarket' => $this->isExportMarket,
            'isImageMore' => $this->isImageMore,
            // 'control' => $this->control,
            'isImageMore2' => $this->isImageMore2,            
            'price' => $this->price,
            'dttm_create' => $this->dttm_create,
            'dttm_modify' => $this->dttm_modify,
            'isReady' => $this->isReady,
            'isArchive' => $this->isArchive,
            'weight' => $this->weight,
            'weight_unit' => $this->weight_unit,
            'length' => $this->length,
            'length_unit' => $this->length_unit,
            'idKitCategory' => $this->idKitCategory,            
        ]);

        $query->andFilterWhere(['like', 'sku', $this->sku])
            // ->andFilterWhere(['like', 'idParent', $idParent])        
            ->andFilterWhere(['like', 'name', $this->name])        
            ->andFilterWhere(['like', 'control', $this->control])                   
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'rack', $this->rack])
            ->andFilterWhere(['like', 'colorName', $this->colorName])
            ->andFilterWhere(['like', 'urlSrc', $this->urlSrc])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'season', $this->season])
            ->andFilterWhere(['like', 'purpose', $this->purpose]);

        return $dataProvider;
    }


    public function search2($params)
    {
        $query = Product::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id1C' => $this->id1C,
            'idCategory' => $this->idCategory,
            'idManufacturer' => $this->idManufacturer,
            'colorCode' => $this->colorCode,
            'isExportMarket' => $this->isExportMarket,
            'isImageMain' => $this->isImageMain,
            'isImageMore' => $this->isImageMore,
            // 'control' => $this->control,
            'isImageMore2' => $this->isImageMore2,            
            'price' => $this->price,
            'dttm_create' => $this->dttm_create,
            'dttm_modify' => $this->dttm_modify,
            'isReady' => $this->isReady,
            'isArchive' => $this->isArchive,
            'weight' => $this->weight,
            'weight_unit' => $this->weight_unit,
            'length' => $this->length,
            'length_unit' => $this->length_unit,
            'idKitCategory' => $this->idKitCategory,            
        ]);

        $query->andFilterWhere(['like', 'sku', $this->sku])  
            ->andFilterWhere(['like', 'name', $this->name])        
            ->andFilterWhere(['like', 'control', $this->control])                   
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'rack', $this->rack])
            ->andFilterWhere(['like', 'colorName', $this->colorName])
            ->andFilterWhere(['like', 'urlSrc', $this->urlSrc])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'season', $this->season])
            ->andFilterWhere(['like', 'purpose', $this->purpose]);

        return $dataProvider;
    }


}
