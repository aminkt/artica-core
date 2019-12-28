<?php

namespace Artica\ApiView;

/**
 * Class BaseApiView
 * An view file to separate view presentation from model or controller.
 * works like view html files but return array instead html for api usages.
 *
 * @package Artica\ApiView
 */
abstract class BaseApiView implements ApiViewInterface
{
    public function __construct()
    {
    }
}
