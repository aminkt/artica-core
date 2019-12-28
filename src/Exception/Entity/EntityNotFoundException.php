<?php

namespace Artica\Exception\Entity;


/**
 * Class EntityNotFoundException
 * Used when entity not found.
 * @package Artica\Exception
 */
class EntityNotFoundException extends EntityException
{
    private $ids;
    private $entityClass;

    public function __construct(string $entityClass, ...$id)
    {
        $this->ids = $id;
        $this->entityClass = $entityClass;
        $message = "Not found - Entity $entityClass";
        $count = count($this->ids);
        if ($count == 1) {
            $message .= ' with ID ' . $this->ids[0];
        } else {
            $message .= ' with IDs: ' . implode(',', $this->ids);
        }
        parent::__construct($message);
    }

    public function getIds(): array
    {
        return $this->ids;
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }
}