<?php

namespace Artica\ApiViews;

use Artica\Entities\BaseEntity;

/**
 * Class EntityView
 * This class can be used to define reprehension of an entity.
 *
 * @package Artica\ApiViews
 */
abstract class EntityView extends BaseView
{
    /** @var BaseEntity $entity */
    protected $entity;

    /**
     * EntityView constructor.
     *
     * @param BaseEntity $entity
     */
    public function __construct(BaseEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Return array presentation of entity.
     *
     * @param BaseEntity $entity
     *
     * @return mixed
     */
    function getData($entity): array
    {
        return $entity->getAttributes();
    }

    /**
     * @inheritDoc
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $data = $this->getData($this->entity);

        $diff = array_diff($data, $fields);
        foreach ($diff as $item) {
            unset($data[$item]);
        }

        return $data;
    }
}
