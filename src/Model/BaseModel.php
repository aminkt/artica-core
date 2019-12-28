<?php
declare(strict_types=1);

namespace Artica\Model;

use Artica\Exception\Model\ValidationException;
use yii\base\Model;

/**
 * Class BaseModel
 * Base model is exactly a business logical model of system objects.
 *
 * @see \Artica\Model\EntityBaseModel
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Model
 */
abstract class BaseModel extends Model
{
    /** @var bool $throwExceptionOnValidationError Throw validation exception if there is validation error. */
    public $throwExceptionOnValidationError = true;

    /**
     * Throw validation exception if there is validation error.
     * @throws ValidationException  When form has validation error for current scenario.
     */
    public function verifyNow(): void
    {
        if (!$this->validate()) {
            throw new ValidationException($this);
        }
    }

    /**
     * @inheritDoc
     * @throws ValidationException
     */
    public function afterValidate()
    {
        parent::afterValidate();
        if ($this->throwExceptionOnValidationError) {
            throw new ValidationException($this);
        }
    }
}
