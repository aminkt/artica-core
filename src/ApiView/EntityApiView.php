<?php

namespace Artica\ApiView;

use Artica\Entity\Entity;

/**
 * Class CrudApiView
 * An view file to separate view presentation from model or controller.
 * This class can be used in crud controllers to provide standard output for every crud action.
 *
 * @package Artica\ApiView
 */
abstract class EntityApiView extends BaseApiView implements EntityApiViewInterface
{
    use EntityApiViewTrait;
}
