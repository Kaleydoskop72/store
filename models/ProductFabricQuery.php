<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProductFabric]].
 *
 * @see ProductFabric
 */
class ProductFabricQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ProductFabric[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProductFabric|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
