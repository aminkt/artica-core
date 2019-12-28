<?php

namespace Artica\ApiView;

use Artica\Entity\Entity;

/**
 * Trait EntityApiViewTrait
 * This trait will implement codes for entity api view classes that implement EntityApiViewInterface.
 *
 * @package Artica\ApiView
 */
trait EntityApiViewTrait
{
    /** @var Entity Related crud */
    protected $entity;

    /**
     * CrudApiView constructor.
     * @param Entity|null $entity
     */
    public function __construct(?Entity $entity)
    {
        parent::__construct();
        $this->entity = $entity;
    }

    /**
     * Return entity class object.
     *
     * @return Entity|null
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
