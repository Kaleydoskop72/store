<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProductKit;

/**
 * ProductKitSearch represents the model behind the search form about `app\models\ProductKit`.
 */
class ProductKitSearch extends ProductKit
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idManufacturer', 'id1C', 'colorCount'], 'integer'],
            [['sku', 'name', 'description', 'thread', 'size', 'sizePack', 'canva', 'author'], 'safe'],
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
        $query = ProductKit::find();

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
            'idManufacturer' => $this->idManufacturer,
            'id1C' => $this->id1C,
            'price' => $this->price,
            'colorCount' => $this->colorCount,
        ]);

        $query->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'thread', $this->thread])
            ->andFilterWhere(['like', 'size', $this->size])
            ->andFilterWhere(['like', 'sizePack', $this->sizePack])
            ->andFilterWhere(['like', 'canva', $this->canva])
            ->andFilterWhere(['like', 'author', $this->author]);

        return $dataProvider;
    }
}
