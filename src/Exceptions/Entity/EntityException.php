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
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Entity Exception';
    }
}