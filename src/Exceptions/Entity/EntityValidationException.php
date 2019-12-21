<?php

namespace Artica\Exceptions\Entity;

use Artica\Entities\Entity;
use Artica\Exceptions\Model\ValidationExceptionInterface;
use Artica\Exceptions\Model\ValidationExceptionTrait;
use Artica\Models\BaseModel;

/**
 * Class EntityException
 *
 * @package Artica\Exceptions
 */
class EntityValidationException extends EntityException implements ValidationExceptionInterface
{
    use ValidationExceptionTrait;

    protected $entity;

    public function __construct(Entity $entity, $message = '', $errorInfo = [], $code = 0, \Exception $previous = null)
    {
        $this->entity = $entity;
        $message .= $this->provideErrorMessage();
        $errorInfo = array_merge($this->getErrors(), $errorInfo);
        parent::__construct($message, $errorInfo, $code, $previous);
    }

    public function getName()
    {
        return 'Entity Validation Exception';
    }

    /**
     * Return model class.
     *
     * @return Entity|BaseModel|null
     */
    public function getModel()
    {
        return $this->entity;
    }
}