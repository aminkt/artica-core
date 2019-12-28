<?php

namespace Artica\Exception\Model;

use Artica\Entity\Entity;
use Artica\Model\BaseModel;
use Throwable;

/**
 * Class EntityException
 *
 * @package Artica\Exception
 */
class ValidationException extends ModelException implements ValidationExceptionInterface
{
    use ValidationExceptionTrait;

    public function __construct(BaseModel $model, $message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($model, $message, $code, $previous);
        $this->message .= $this->provideErrorMessage();
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'Validation Exception';
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return parent::__toString() . PHP_EOL
            . 'Errors:' . PHP_EOL . print_r($this->getErrors(), true);
    }

    /**
     * Return model class.
     *
     * @return Entity|BaseModel|null
     */
    public function getModel()
    {
        return $this->model;
    }
}