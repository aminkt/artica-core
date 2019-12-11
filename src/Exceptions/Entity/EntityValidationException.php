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