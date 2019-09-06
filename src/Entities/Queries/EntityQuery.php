<?php
declare(strict_types=1);

namespace Artica\Entities\Queries;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\HangApp\Entities\EventEntity]].
 *
 * @see \Artica\Entities\Entity
 */
class EntityQuery extends ActiveQuery
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
     * @return \Artica\Entities\Entity[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \Artica\Entities\Entity[]|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
