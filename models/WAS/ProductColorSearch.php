<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProductColor;

/**
 * ProductColorSearch represents the model behind the search form about `app\models\ProductColor`.
 */
class ProductColorSearch extends ProductColor
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idProduct', 'isImageMain', 'isImageMore', 'idImage'], 'integer'],
            [[ 'id1C', 'comment', 'location', 'sku', 'colorCode', 'colorName'], 'safe'],
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
    public function search($params, $product_id)
    {
        $query = ProductColor::find();

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
            'idProduct' => $this->idProduct,
            'isImageMain' => $this->isImageMain,
            'isImageMore' => $this->isImageMore,      
            'id1C' => $this->id1C,
            'comment' => $this->comment,
            'location' => $this->location,                       
        ]);

        $query->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'colorCode', $this->colorCode])
            ->andFilterWhere(['like', 'idProduct', $product_id])       
            ->andFilterWhere(['like', 'id1C', $this->id1C])  
            ->andFilterWhere(['like', 'comment', $this->comment])  
            ->andFilterWhere(['like', 'location', $this->location])                                                    
            ->andFilterWhere(['like', 'colorName', $this->colorName]);

        return $dataProvider;
    }
}
