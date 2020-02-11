<?php

namespace Artica\Exception;

use Artica\Entity\Entity;
use Artica\Model\BaseModel;
use Throwable;
use yii\base\Exception;

/**
 * Class EntityException
 * Used to trhow an exception because of input validation errors.
 *
 * @package Artica\Exception
 */
class ValidateException extends Exception implements ValidationExceptionInterface
{
    public $errors = [];

    public function __construct(string $message = "", string $propertyPath = '')
    {
        parent::__construct($message, 0, null);
        if (!empty($propertyPath)) {
            $this->errors[$propertyPath] = $message;
        } else {
            $this->errors[] = $message;
        }
    }

    /**
     * Return model errors.
     *
     * @param string|null   $attribute
     *
     * @return array
     */
    public function getErrors(?string $attribute = null)
    {
        return ($attribute === null) ? $this->errors : $this->errors[$attribute];
    }

    /**
     * Return model class.
     *
     * @return Entity|BaseModel|null
     */
    public function getModel()
    {
        return null;
    }
}