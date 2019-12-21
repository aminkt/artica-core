<?php
declare(strict_types=1);

namespace Artica\Forms;

use Artica\Entities\Entity;
use Artica\Exceptions\Entity\EntityNotFoundException;
use Artica\Exceptions\Entity\EntityValidationException;
use BadMethodCallException;
use yii\base\Model;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class CrudForm
 * Base class for Artica Crud Form.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Forms
 *
 *
 * @property-read  null|string $entityClass
 */
abstract class CrudForm extends BaseForm
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';

    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        return array_merge(
            parent::scenarios(),
            [
                self::SCENARIO_CREATE,
                self::SCENARIO_UPDATE,
                self::SCENARIO_DELETE,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function formName(): string
    {
        if ($this->formName != null) {
            return $this->formName;
        }

        return parent::formName();
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        $rules = [];

        foreach ($this->createRules() as $rule) {
            $rule['on'] = self::SCENARIO_CREATE;
            $rules[] = $rule;
        }

        foreach ($this->updateRules() as $rule) {
            $rule['on'] = self::SCENARIO_UPDATE;
            $rules[] = $rule;
        }

        foreach ($this->deleteRules() as $rule) {
            $rule['on'] = self::SCENARIO_DELETE;
            $rules[] = $rule;
        }

        return array_merge(parent::rules(), $rules);
    }

    /**
     * Return create scenario rules.
     *
     * @see \Artica\Forms\BaseForm::rules()
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    abstract protected function createRules(): array;

    /**
     * Return create scenario rules.
     *
     * @see \Artica\Forms\BaseForm::rules()
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function updateRules(): array
    {
        return $this->createRules();
    }

    /**
     * Return create scenario rules.
     *
     * @see \Artica\Forms\BaseForm::rules()
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function deleteRules(): array
    {
        return [];
    }

    /**
     * Return related entity class name.
     *
     * @return string|null
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    abstract public function getEntityClass(): string;

    /**
     * Create operation of crud form.
     *
     * @return Entity|null
     *
     * @throws EntityValidationException When can't save entity.
     * @throws ServerErrorHttpException When can't save entity.
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function create(): ?Entity
    {
        $this->setScenario(self::SCENARIO_CREATE);

        if (!$this->validate()) {
            return null;
        }

        /** @var Entity $entity */
        $class = $this->getEntityClass();
        $entity = new $class();

        $attributes = $this->entityAttributesMapper();

        if (!$entity->load($attributes, '')) {
            throw new BadMethodCallException('Can\'t load entity '. $this->getEntityClass());
        }

        if (!$entity->save()) {
            $this->handleErrors($entity);
        }

        return $entity;
    }

    /**
     * Update operation for crud form.
     *
     * @param mixed $id Id of entity.
     *
     * @return Entity|null
     *
     * @throws NotFoundHttpException Throw when can't find entity.
     * @throws EntityValidationException When can't save entity.
     * @throws ServerErrorHttpException When can't save entity.
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function update($id): ?Entity
    {
        $this->setScenario(self::SCENARIO_UPDATE);

        if (!$this->validate()) {
            return null;
        }

        $entity = $this->loadEntityById($id);

        $attributes = $this->entityAttributesMapper();
        if (!$entity->load($attributes, '')) {
            throw new BadMethodCallException('Can\'t load entity '. $this->getEntityClass());
        }

        if (!$entity->update()) {
            $this->handleErrors($entity);
        }

        return $entity;
    }

    /**
     * Delete related entity.
     * You can overwrite this method to change logic of delete.
     *
     * @param mixed $id Id of entity.
     *
     * @return bool
     *
     * @throws NotFoundHttpException
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function delete($id): bool
    {
        $this->setScenario(self::SCENARIO_DELETE);

        if (!$this->validate()) {
            return false;
        }

        $entity =  $this->loadEntityById($id);

        return $entity->delete() > 0 ? true : false;
    }

    /**
     * Find entity and return it by id.
     *
     * @param $id
     *
     * @return Entity
     *
     * @throws NotFoundHttpException Throw when can't find entity.
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function loadEntityById($id): Entity
    {
        /** @var Entity $entityClass */
        $entityClass = $this->getEntityClass();

        try {
            $entity = $entityClass::getById($id);
        } catch (EntityNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }

        return $entity;
    }

    /**
     * Map entity attributes to form attributes.
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function entityAttributesMapper(): array
    {
        $attributes = [];
        foreach ($this->activeAttributes() as $attribute) {
            $attributes[$attribute] = $this->$attribute;
        }
        return $attributes;
    }

    /**
     * Handle entity errors when try to save or update or delete an entity.
     *
     * @param Entity $entity
     *
     * @return void
     *
     * @throws EntityValidationException
     * @throws ServerErrorHttpException
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function handleErrors(Entity $entity): void
    {
        if ($entity->hasErrors()) {
            throw new EntityValidationException($entity);
        }

        throw new ServerErrorHttpException('Can not save entity');
    }
}
