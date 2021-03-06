<?php
declare(strict_types=1);

namespace Artica\Model;

use Artica\Entity\Entity;

/**
 * Class EntityBaseModel
 * EntityBaseModel is exactly a business logical model of Entity classes. because Entity classes just should handle database logic,
 * you can use this type of classes to handle logical rules. like interfaces you should implement for user entity can be implement
 * in this class.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @see \Artica\Entity\Entity
 *
 * @package Artica\Model
 */
abstract class EntityBaseModel extends BaseModel
{
    private $_entity = null;

    /**
     * EntityBaseModel constructor.
     *
     * @param Entity|null $entity
     * @param array       $config
     */
    public function __construct(?Entity $entity, $config = [])
    {
        parent::__construct($config);
        $this->_entity = $entity;
    }

    /**
     * Return related entity object.
     *
     * @return Entity|null
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getEntity(): ?Entity
    {
        return $this->_entity;
    }
}
