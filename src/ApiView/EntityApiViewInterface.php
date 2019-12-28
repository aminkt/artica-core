<?php

namespace Artica\ApiView;

use Artica\Entity\Entity;

/**
 * Interface EntityApiViewInterface
 * Used to define EntityApiView object abstractions.
 *
 * @package Artica\ApiView
 */
interface EntityApiViewInterface extends ApiViewInterface
{
    /**
     * Return entity class object.
     *
     * @return Entity|null
     */
    public function getEntity();
}
