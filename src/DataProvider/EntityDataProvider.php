<?php

namespace Artica\DataProvider;

use Artica\ApiView\EntityApiViewInterface;
use Artica\Entity\EntityInterface;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;

/**
 * Class EntityDataProvider
 * Provide data base on entity class.
 * @package Artica\DataProvider
 */
class EntityDataProvider extends ActiveDataProvider
{
    /** @var string $viewClass View class to name to provide output base on entity. */
    public $viewClass;

    /**
     * @inheritDoc
     */
    public function prepareModels()
    {
        $entities = parent::prepareModels();

        if (!class_exists($this->viewClass) || !is_subclass_of($this->viewClass, EntityApiViewInterface::class)) {
            throw new InvalidConfigException('The "viewClass" property must be an instance of a class that extends the EntityApiView.');
        }

        $views = [];
        $viewClassName = $this->viewClass;
        /** @var EntityInterface $entity */
        foreach ($entities as $entity) {
            $views[$entity->getId()] = new $viewClassName($entity);
        }

        return $views;
    }

    /**
     * @inheritDoc
     */
    public function prepareKeys($models)
    {
        return array_keys($models);
    }
}