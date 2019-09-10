<?php
declare(strict_types=1);

namespace Artica\Forms;

use Artica\Entities\Entity;
use BadMethodCallException;
use yii\web\NotFoundHttpException;

/**
 * Class CrudForm
 * Base class for Artica Crud Form.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Forms
 *
 */
abstract class CrudForm extends BaseForm
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';

    /** @var null|string $formName Form name. If null use class name to generate form name. */
    protected $formName = null;

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

        foreach ($this->getCreateRules() as $rule) {
            $rule['on'] = self::SCENARIO_CREATE;
            $rules[] = $rule;
        }

        foreach ($this->getUpdateRules() as $rule) {
            $rule['on'] = self::SCENARIO_UPDATE;
            $rules[] = $rule;
        }

        foreach ($this->getDeleteRules() as $rule) {
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
    abstract protected function getCreateRules(): array;

    /**
     * Return create scenario rules.
     *
     * @see \Artica\Forms\BaseForm::rules()
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    abstract protected function getUpdateRules(): array;

    /**
     * Return create scenario rules.
     *
     * @see \Artica\Forms\BaseForm::rules()
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    abstract protected function getDeleteRules(): array;

    /**
     * Return related entity class name.
     *
     * @return string|null
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    abstract protected function getEntityClass(): string;

    /**
     * Create operation of crud form.
     *
     * @return \Artica\Entities\Entity|null
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \BadMethodCallException Throw when can't load entity object.
     */
    public function create(): ?Entity
    {
        $this->setScenario(self::SCENARIO_CREATE);

        if (!$this->validate()) {
            return null;
        }

        /** @var Entity $entity */
        $entity = new ($this->getEntityClass())();

        $attributes = $this->entityAttributesMapper();

        if (!$entity->load($attributes, '')) {
            throw new BadMethodCallException('Can\'t load entity object.');
        }

        if (!$entity->save()) {
            $this->handleEntityErrors($entity);
        }

        return $entity;
    }

    /**
     * Update operation for crud form.
     *
     * @param mixed $id Id of entity.
     *
     * @return \Artica\Entities\Entity|null
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \BadMethodCallException Throw when can't load entity object.
     * @throws \yii\web\NotFoundHttpException Throw when can't find entity.
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
            throw new BadMethodCallException('Can\'t load entity object.');
        }

        if (!$entity->update()) {
            $this->handleEntityErrors($entity);
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
     * @return \Artica\Entities\Entity
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \yii\web\NotFoundHttpException Throw when can't find entity.
     */
    private function loadEntityById($id): Entity
    {
        /** @var Entity $entityClass */
        $entityClass = $this->getEntityClass();
        $entity = $entityClass::findOne($id);
        if (!$entity) {
            throw new NotFoundHttpException('Can\'t find entity');
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
     * @param \Artica\Entities\Entity $entity
     *
     * @return void
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function handleEntityErrors(Entity $entity): void
    {
        foreach ($entity->getErrors() as $attribute => $error) {
            if (is_array($error)) {
                foreach ($error as $errorMessage) {
                    $this->addError($attribute, $errorMessage);
                }
            } else {
                $this->addError($attribute, $error);
            }
        }
    }
}
