<?php

namespace Artica\Entities\Queries;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\HangApp\Entities\EventEntity]].
 *
 * @see \HangApp\Entities\EventEntity
 */
abstract class EntityQuery extends ActiveQuery
{
    /**
     * Return not deleted rows.
     *
     * @return \Artica\Entities\Queries\EntityQuery
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function alive()
    {
        return $this->andWhere([
            'is_deleted' => false
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \HangApp\Entities\EventEntity[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \HangApp\Entities\EventEntity|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
