<?php
declare(strict_types=1);

namespace Artica\Models;

use Artica\Exceptions\Model\ValidationException;
use yii\base\Model;

/**
 * Class BaseModel
 * Base model is exactly a business logical model of system objects.
 *
 * @see \Artica\Models\EntityBaseModel
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Models
 */
abstract class BaseModel extends Model
{
    /**
     * Throw validation exception if there is validation error.
     *
     * @throws ValidationException  When form has validation error for current scenario.
     */
    public function verifyNow(): void
    {
        if (!$this->validate()) {
            throw new ValidationException($this);
        }
    }
}
