<?php

namespace Artica\Exception\Model;

use Artica\Model\BaseModel;
use Throwable;
use yii\base\Exception;

/**
 * Class ModelException
 *
 * @package Artica\Exception
 */
abstract class ModelException extends Exception
{
    /** @var BaseModel $model */
    protected $model;

    public function __construct(BaseModel $model, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->model = $model;
        parent::__construct($message, $code, $previous);
    }

    public function getName()
    {
        return 'Model Exception';
    }
}