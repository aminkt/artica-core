<?php
declare(strict_types=1);


namespace Artica\Entities;

use Artica\Entities\Queries\EntityQuery;
use Artica\Exceptions\Entity\EntityNotFoundException;
use Exception;
use RuntimeException;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\StaleObjectException;

/**
 * Class Entity
 * This class contain database logic.
 *
 * @property mixed $id
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Entities
 */
abstract class Entity extends BaseEntity
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}
