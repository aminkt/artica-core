<?php

namespace Artica\Exceptions\Entity;

use Artica\Entities\Entity;

/**
 * Class EntityException
 *
 * @package Artica\Exceptions
 */
class EntityValidationException extends EntityException
{
    protected $entity;

    public function __construct(Entity $entity, $message = '', $errorInfo = [], $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $errorInfo, $code, $previous);
        $this->entity = $entity;
    }

    public function getName()
    {
        return 'Entity Validation Exception';
    }

    /**
     * Return entity errors.
     *
     * @param string|null $attribute
     *
     * @return array
     */
    public function getErrors(?string $attribute = null)
    {
        return $this->entity->getErrors($attribute);
    }

    public function __toString()
    {
        $message = parent::__toString();
        if ($this->entity->hasErrors()) {
            $message .= PHP_EOL . 'Validation Errors:' . PHP_EOL;
            $message .= json_encode($this->entity->getErrors());
        }
        return $message;
    }
}