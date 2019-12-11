<?php

namespace Artica\Exceptions\Entity;

use Artica\Entities\Entity;
use yii\db\Exception;

/**
 * Class EntityException
 * @package Artica\Exceptions
 */
class EntityException extends Exception
{
    protected $entity;

    public function __construct(Entity $entity, $message = '', $errorInfo = [], $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $errorInfo, $code, $previous);
        $this->entity = $entity;
    }

    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Entity Exception';
    }
}