<?php
declare(strict_types=1);

namespace Artica\Entities;

use Artica\Entities\Queries\EntityQuery;
use yii\db\Query;

/**
 * Class EntityCacheIndex
 * Define cache index for entity.
 *
 * @see Entity::getCacheIndexes()
 * @see EntityQuery::byCacheIndex()
 *
 * @package Artica\Entities
 */
class EntityCacheIndex
{
    protected $dynamicFields;
    protected $staticFields;

    /**
     * EntityCacheIndex constructor.
     *
     * @param array $dynamicFields  List of dynamic fields.
     * @param array $staticFields   List of static fields by them values.
     */
    public function __construct( array $dynamicFields = [], array $staticFields = [])
    {
        $this->dynamicFields = $dynamicFields;
        $this->staticFields = $staticFields;
    }

    /**
     * Return cache dynamic fields.
     *
     * @return array
     */
    public function getDynamicFields(): array
    {
        return $this->dynamicFields;
    }

    /**
     * Return cache static fields by its value.
     * For example
     * <code>
     * [
     *    'active' => true,
     *    'status' => 'confirm'
     * ]
     * </code>
     * mean where condition should use ```active = true AND status = 'confirm'```.
     *
     * @return array
     */
    public function getStaticFields(): array
    {
        return $this->staticFields;
    }

    /**
     * Generate cache where query using index divination.
     *
     * @param Query $query  Query builder object.
     * @param array $dynamicValues List of values sort by dynamicFields definition.
     *
     * @return Query
     */
    public function generateWhereCondition(Query $query, array $dynamicValues): Query
    {
        if (!empty($this->getStaticFields())) {
            $query->andWhere($this->getStaticFields());
        }

        if (!empty($this->getDynamicFields())) {
            $dynamicFields = [];
            foreach ($dynamicFields as $index => $value) {
                $dynamicFields[$value] = $dynamicValues[$index];
            }
            $query->andWhere($dynamicFields);
        }

        return $query;
    }
}
