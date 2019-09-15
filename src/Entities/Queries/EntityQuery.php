<?php
declare(strict_types=1);

namespace Artica\Entities\Queries;

use Artica\Entities\Entity;
use Artica\Entities\EntityCacheIndex;
use Artica\Exceptions\EntityException;
use Artica\Services;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * This is the ActiveQuery class for [[\HangApp\Entities\EventEntity]].
 *
 * @see \Artica\Entities\Entity
 */
class EntityQuery extends ActiveQuery
{
    use Services;

    /**
     * Return not deleted rows.
     *
     * @return EntityQuery
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function alive(): EntityQuery
    {
        return $this->andWhere([
            'is_deleted' => false
        ]);
    }

    /**
     * {@inheritdoc}
     * @return Entity[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Entity[]|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Add id to sql query where.
     *
     * @param array|int|string $id Entity id or Entity ids. Also you can pass array of ids.
     *
     * @return EntityQuery
     */
    public function byId($id): EntityQuery
    {
        return $this->where([
            'id' => $id
        ]);
    }

    /**
     * Use index cache code and conditions to find entity ids from cache, then create database query to find entity by ids
     * that provided from cache.
     *
     * @param string $indexCode
     * @param array $dynamicFieldValues Dynamic fields values sort by index definition.
     *
     * @return EntityQuery
     */
    public function byCacheIndex(string $indexCode, array $dynamicFieldValues): EntityQuery
    {
        $ids = $this->getIdsFromCache($indexCode, $dynamicFieldValues);

        $this->where([
            'id' => $ids
        ]);

        return $this;
    }

    /**
     * Return list of ids from cache by given cache code and conditions.
     *
     * @param string $indexCode
     * @param array $dynamicValues
     *
     * @return array
     */
    private function getIdsFromCache(string $indexCode, array $dynamicValues): array
    {
        $getIdsCallable = function () use ($indexCode, $dynamicValues) : string {
            /** @var Entity $entityClass */
            $entityClass = $this->modelClass;
            $indexItems = $entityClass::getCacheIndexes();
            if (!isset($indexItems[$indexCode])) {
                throw new EntityException('Cache index code is not valid.');
            }

            $query = (new Query())
                ->select(['id'])
                ->from($entityClass::tableName());

            /** @var EntityCacheIndex $indexItem */
            $indexItem = $indexItems[$indexCode];
            $query = $indexItem->generateWhereCondition($query, $dynamicValues);

            if ($indexItem->isUnique()) {
                $res = $query->one();
            } else {
                $res = $query->all();
            }

            return  implode(array_column($res, 'id'), ',');
        };

        $ids = $this->serviceRedisCache()->getOrSet(
            $indexCode,
            $getIdsCallable
        );

        return empty($ids) ? [] : explode(',', $ids);
    }
}
