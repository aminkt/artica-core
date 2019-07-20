<?php


namespace Artica\Entities;

use \RuntimeException;
use Yii;
use yii\db\QueryBuilder;

/**
 * Class Flusher
 * Use flusher to update, delete or insert multi entity.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Entities
 *
 */
class Flusher
{
    /** @var Entity[] $insertEntities*/
    private $insertEntities = [];
    /** @var Entity[] $updateEntities*/
    private $updateEntities = [];
    /** @var Entity[] $deleteEntities*/
    private $deleteEntities = [];

    public function registerEntity(Entity $entity, bool $toDelete = false): Flusher
    {
        if ($entity->getIsNewRecord()) {
            $this->insertEntities[] = $entity;
        } elseif ($toDelete) {
            $this->deleteEntities[] = $entity;
        } else {
            $this->updateEntities[] = $entity;
        }

        return $this;
    }

    public function resetFlusher(): Flusher
    {
        $this->insertEntities = [];
        $this->updateEntities = [];
        $this->deleteEntities = [];
        return $this;
    }

    public function lazyFlush(): void
    {
        throw new RuntimeException("Not implemented yet.");
    }

    public function flush(): bool
    {
        return $this->insertQuery() == 0 ? false :
            $this->updateQuery() == 0 ? false : true;
    }

    public function delete(bool $force): bool
    {
        return $this->deleteQuery($force) > 0 ? true : false;
    }

    private function deleteQuery(bool $force = false): int
    {
        $queryBuilder = new QueryBuilder(Yii::$app->getDb());
        $queryString = '';
        foreach ($this->deleteEntities as $entity) {
            $params = [];
            if ($entity->isSoftDeleteActive() and $force == false) {
                $queryString .= $queryBuilder->update(
                    $entity::tableName(),
                    [$entity->softDeleteFieldName => true],
                    ['id' => $entity->getId()],
                    $params
                );
            } else {
                $queryString .= $queryBuilder->delete(
                    $entity::tableName(),
                    ['id' => $entity->getId()],
                    $params
                );
            }

        }
        return Yii::$app->getDb()->createCommand($queryString)->execute();
    }

    private function updateQuery(): int
    {
        $queryBuilder = new QueryBuilder(Yii::$app->getDb());
        $queryString = '';
        foreach ($this->updateEntities as $entity) {
            $params = [];
            $queryString .= $queryBuilder->update(
                $entity::tableName(),
                $entity->attributes(),
                ['id' => $entity->getId()],
                $params
            );
        }
        return Yii::$app->getDb()->createCommand($queryString)->execute();
    }

    private function insertQuery(): int
    {
        $queryBuilder = new QueryBuilder(Yii::$app->getDb());
        $queryString = '';
        foreach ($this->insertEntities as $entity) {
            $params = [];
            $queryString .= $queryBuilder->insert($entity::tableName(), $entity->attributes(), $params);
        }
        return Yii::$app->getDb()->createCommand($queryString)->execute();
    }
}