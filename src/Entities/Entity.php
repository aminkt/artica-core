<?php


namespace Artica\Entities;

use Artica\Entities\Queries\EntityQuery;
use Exception;
use RuntimeException;
use Throwable;
use Yii;
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
abstract class Entity extends ActiveRecord
{
    public $softDeleteFieldName = 'is_deleted';

    protected static $queryClass = null;

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
     * > Note: If soft delete field not defined or not availbale, then using regular delete.
     *
     * In the above step 1 and 3, events named [[EVENT_BEFORE_DELETE]] and [[EVENT_AFTER_DELETE]]
     * will be raised by the corresponding methods.
     *
     * @param bool $force   Force to delete record from database or use soft delete if available.
     *
     * @return int|false the number of rows deleted, or `false` if the deletion is unsuccessful for some reason.
     * Note that it is possible the number of rows deleted is 0, even though the deletion execution is successful.
     * @throws StaleObjectException if [[optimisticLock|optimistic locking]] is enabled and the data
     * being deleted is outdated.
     * @throws \Exception|\Throwable in case delete failed.
     */
    public function delete(bool $force = false)
    {
        if (!$this->isTransactional(self::OP_DELETE)) {
            return $this->deleteInternal($force);
        }

        $transaction = static::getDb()->beginTransaction();
        try {
            $result = $this->deleteInternal($force);
            if ($result === false) {
                $transaction->rollBack();
            } else {
                $transaction->commit();
            }

            return $result;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Deletes an ActiveRecord without considering transaction.
     *
     * @param bool $force Force to delete record and don't using softdelete.
     *
     * @return int|false the number of rows deleted, or `false` if the deletion is unsuccessful for some reason.
     * Note that it is possible the number of rows deleted is 0, even though the deletion execution is successful.
     * @throws StaleObjectException
     * @throws \yii\db\Exception
     */
    protected function deleteInternal(bool $force = false)
    {
        if (!$this->beforeDelete()) {
            return false;
        }

        // we do not check the return value of deleteAll() because it's possible
        // the record is already deleted in the database and thus the method will return 0
        $condition = $this->getOldPrimaryKey(true);
        $lock = $this->optimisticLock();
        if ($lock !== null) {
            $condition[$lock] = $this->$lock;
        }
        if ($force) {
            $result = static::deleteAll($condition);
        } else {
            $result = static::updateAll([$this->softDeleteFieldName => true], $condition);
        }
        if ($lock !== null && !$result) {
            throw new StaleObjectException('The object being deleted is outdated.');
        }
        $this->setOldAttributes(null);
        $this->afterDelete();

        return $result;
    }

    /**
     * Check if soft delete functionlaity is ok or not.
     *
     * @return bool
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function isSoftDeleteActive(): bool
    {
        return ($this->softDeleteFieldName and $this->hasAttribute($this->softDeleteFieldName));
    }

    /**
     * Is record sof deleted or not.
     *
     * @return bool true if soft deleted and false if not.
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function isSoftDeleted(): bool
    {
        return $this->isSoftDeleteActive() ? $this->{$this->softDeleteFieldName} : false;
    }

    /**
     * Return id of
     *
     * @return mixed
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getId()
    {
        if ($this->hasAttribute('id')) {
            return $this->id;
        }

        throw new RuntimeException('Not implemented');
    }

    /**
     * {@inheritdoc}
     * @return \Artica\Entities\Queries\EntityQuery|object
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        $queryClass = static::$queryClass ? static::$queryClass : EntityQuery::class;

        return Yii::createObject($queryClass, [get_called_class()]);
    }
}
