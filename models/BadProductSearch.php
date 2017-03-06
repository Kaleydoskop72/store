<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BadProduct;

/**
 * BadProductSearch represents the model behind the search form about `app\models\BadProduct`.
 */
class BadProductSearch extends BadProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idProduct', 'idCategory', 'id1C'], 'integer'],
            [['message'], 'safe'],
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
        $query = BadProduct::find();

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
            'idProduct' => $this->idProduct,
        ]);
        $query->andFilterWhere([
            'id1C' => $this->id1C,
        ]);        
        $query->andFilterWhere([
            'idCategory' => $this->idCategory, 
        ]);

        $query->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
