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
            [['id', 'idCategory', 'isImageMain', 'id1C', 'idManufacturer', 'weight', 'width', 'length'], 'integer'],
            [['id1C', 'comment', 'location', 'name', 'description', 'structure', 'season', 'purpose', 'sku', 'urlSrc', 'colorName', 'colorCode'], 'safe'],
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
    public function search($params)
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
            'idCategory' => $this->idCategory,
            'isImageMain' => $this->isImageMain,
            'id1C' => $this->id1C,
            'idManufacturer' => $this->idManufacturer,
            'weight' => $this->weight,
            'width' => $this->width,
            'length' => $this->length,
            'price' => $this->price,
            'comment' => $this->comment,
            'location' => $this->location,        
            'colorCode' => $this->colorCode,
            'colorName' => $this->colorName,                                                    
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'structure', $this->structure])
            ->andFilterWhere(['like', 'season', $this->season])
            ->andFilterWhere(['like', 'purpose', $this->purpose])
            ->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'id1C', $this->id1C])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'location', $this->location])  
            ->andFilterWhere(['like', 'colorName', $this->colorName])  
            ->andFilterWhere(['like', 'colorCode', $this->colorCode])                                              
            ->andFilterWhere(['like', 'urlSrc', $this->urlSrc]);

        return $dataProvider;
    }
}
