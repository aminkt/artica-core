<?php

namespace Artica\Entity;

use Artica\Exception\Entity\EntityException;
use Artica\Exception\Entity\EntityValidationException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Class Entity
 * This class contain database logic.
 *
 * @property mixed $id
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Entity
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

    /**
     * @inheritDoc
     * @throws EntityValidationException
     */
    public function afterValidate()
    {
        parent::afterValidate();
        if ($this->hasErrors()) {
            throw new EntityValidationException($this);
        }
    }

    /**
     * @inheritDoc
     * @throws EntityException  When can not save entity for unknown reason.
     * @throws EntityValidationException When can not save entity because of validation error.
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $result = parent::save($runValidation, $attributeNames);
        if (!$result) {
            $this->handleEntityUpdateFails();
        }
        return $result;
    }

    /**
     * @inheritDoc
     * @throws EntityException  When can not save entity for unknown reason.
     * @throws EntityValidationException When can not save entity because of validation error.
     */
    public function update($runValidation = true, $attributeNames = null)
    {
        $result = parent::update($runValidation, $attributeNames);
        if (!$result) {
            $this->handleEntityUpdateFails();
        }
        return $result;
    }

    /**
     * Handle entity update fails.
     * @throws EntityException
     * @throws EntityValidationException
     */
    private function handleEntityUpdateFails()
    {
        if ($this->hasErrors()) {
            throw new EntityValidationException($this);
        } else {
            throw new EntityException('Can not save entity '. get_called_class() .'.');
        }
    }
}
