<?php

namespace Artica\ApiView;

use Artica\Entity\Entity;
use Artica\Exception\View\ViewNotFoundException;
use Yii;

/**
 * Class ControllerApiView
 * An view file to separate view presentation from model or controller.
 * This class can be used in controllers to provide standard output for every action.
 * To use this class you should extend your controller view from this class and implement a method in pattern `actionResponse`.
 * Replace action with controller action. For example `indexResponse` to return standard response for indexAction in your controller.
 *
 * @package Artica\ApiView
 */
abstract class ControllerApiView extends BaseApiView
{
    /** @var string Current action. */
    protected $action;
    /** @var string Current controller */
    protected $controller;
    /** @var string View scenario */
    protected $scenario;

    /**
     * CrudApiView constructor.
     */
    public function __construct()
    {
        $this->controller = Yii::$app->controller->id;
        $this->action = Yii::$app->controller->action->id;
        $this->scenario = $this->action;
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        $methodName = $this->scenario . 'Response';
        return call_user_func([$this, $methodName]);
    }
}
