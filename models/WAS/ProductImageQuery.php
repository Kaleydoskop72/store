<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProductImage]].
 *
 * @see ProductImage
 */
class ProductImageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ProductImage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProductImage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
