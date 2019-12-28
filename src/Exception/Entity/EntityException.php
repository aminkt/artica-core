<?php

namespace Artica\Exception\Entity;

use yii\db\Exception;

/**
 * Class EntityException
 * @package Artica\Exception
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