<?php

namespace Artica\Exception\Model;

use Artica\Entity\Entity;
use Artica\Model\BaseModel;

/**
 * Interface ValidationExceptionInterface
 *
 * @package Artica\Exception\Model
 */
interface ValidationExceptionInterface
{
    /**
     * Return model errors.
     *
     * @param string|null $attribute
     *
     * @return array
     */
    public function getErrors(?string $attribute = null);

    /**
     * Return model class.
     *
     * @return Entity|BaseModel|null
     */
    public function getModel();
}