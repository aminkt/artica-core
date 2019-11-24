<?php
declare(strict_types=1);


namespace Artica\Entities;

use Artica\Entities\Queries\EntityQuery;
use Exception;
use RuntimeException;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
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
}
