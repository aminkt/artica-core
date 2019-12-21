<?php

namespace Artica\Exceptions\Model;

use Artica\Entities\Entity;
use Artica\Models\BaseModel;

/**
 * Interface ValidationExceptionInterface
 *
 * @package Artica\Exceptions\Model
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