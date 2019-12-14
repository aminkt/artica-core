<?php
declare(strict_types=1);


namespace Artica\Entities;

use Artica\ApiViews\EntityView;
use Artica\Entities\Queries\EntityQuery;
use Artica\Exceptions\View\ViewNotFoundException;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;

/**
 * Class Entity
 * This class contain database logic.
 *
 * @property mixed $id
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Entities
 */
interface EntityInterface extends ActiveRecordInterface
{
    /**
     * Deletes the table row corresponding to this active record.
     *
     * This method performs the following steps in order:
     *
     * 1. call [[beforeDelete()]]. If the method returns `false`, it will skip the
     *    rest of the steps;
     * 2. delete the record from the database;
     * 3. call [[afterDelete()]].
     *
     * In the above step 1 and 3, events named [[EVENT_BEFORE_DELETE]] and [[EVENT_AFTER_DELETE]]
     * will be raised by the corresponding methods.
     *
     * @param bool $force Force to delete without using soft delete option.
     *
     * @return int|false the number of rows deleted, or `false` if the deletion is unsuccessful for some reason.
     * Note that it is possible the number of rows deleted is 0, even though the deletion execution is successful.
     */
    public function delete(bool $force = false);

    /**
     * Check if soft delete functionlaity is ok or not.
     *
     * @return bool
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function isSoftDeleteActive(): bool;

    /**
     * Is record soft deleted or not.
     *
     * @return bool true if soft deleted and false if not.
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function isSoftDeleted(): bool;

    /**
     * Return id of
     *
     * @return mixed
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getId();

    /**
     * {@inheritdoc}
     * @return EntityQuery|object
     * @throws InvalidConfigException
     */
    public static function find(): EntityQuery;

    /**
     * Returns attribute values.
     * @param array $names list of attributes whose value needs to be returned.
     * Defaults to null, meaning all attributes listed in [[attributes()]] will be returned.
     * If it is an array, only the attributes in the array will be returned.
     * @param array $except list of attributes whose value should NOT be returned.
     * @return array attribute values (name => value).
     */
    public function getAttributes($names = null, $except = []);
}
