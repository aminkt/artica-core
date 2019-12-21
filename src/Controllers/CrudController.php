<?php

namespace Artica\Controllers;

use Artica\ApiViews\CrudApiView;
use Artica\Entities\Entity;
use Artica\Exceptions\Entity\EntityNotFoundException;
use Artica\Exceptions\Entity\EntityValidationException;
use Artica\Exceptions\Model\ValidationException;
use Artica\Forms\CrudForm;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class CrudController
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Controllers
 *
 * @property-read  string $apiViewClass
 * @property-read  CrudForm $crudForm
 */
abstract class CrudController extends RestController
{
    /**
     * Return Crud form object.
     *
     * @return CrudForm
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    abstract protected function getCrudForm();

    /**
     * Return Crud Api view class name.
     * @return string
     */
    abstract protected function getApiViewClass(): string;

    /**
     * View an item using id.
     *
     * @param mixed $id Item id.
     *
     * @return CrudApiView
     */
    public function actionView($id)
    {
        /** @var Entity $entityClass */
        $entityClass = $this->getCrudForm()->getEntityClass();

        try {
            $entity = $entityClass::getById($id);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException();
        }

        $apiViewClass = $this->getApiViewClass();
        return new $apiViewClass($entity);
    }

    /**
     * Return list of items.
     */
    public function actionIndex()
    {
        throw new ServerErrorHttpException('Not Implemented yet!');
    }

    /**
     * Create a new item using CrudForm.
     *
     * @return CrudForm|CrudApiView
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function actionCreate()
    {
        $form = $this->getCrudForm();
        $form->setScenario($form::SCENARIO_CREATE);

        if (!$form->load(Yii::$app->getRequest()->post())) {
            throw new BadRequestHttpException('Can\'t load form.');
        }

        try {
            $entity = $form->create();
        } catch (ValidationException $e) {
            return $form;
        }

        $apiViewClass = $this->getApiViewClass();
        return new $apiViewClass($entity);
    }

    /**
     * Update entity using crud form definition.
     *
     * @param mixed $id Entity id.
     *
     * @return CrudForm|CrudApiView
     */
    public function actionUpdate($id)
    {
        $form = $this->getCrudForm();
        $form->setScenario($form::SCENARIO_UPDATE);

        if (!$form->load(Yii::$app->getRequest()->post())) {
            throw new BadRequestHttpException('Can\'t load form.');
        }

        try {
            $entity = $form->u();
        } catch (ValidationException $e) {
            return $form;
        }

        $apiViewClass = $this->getApiViewClass();
        return new $apiViewClass($entity);
    }

    /**
     * Delete an item using Crud form.
     *
     * @param mixed $id Item id.
     * @return CrudForm|CrudApiView
     */
    public function actionDelete($id)
    {
        $form = $this->getCrudForm();
        $form->setScenario($form::SCENARIO_DELETE);

        if (!$form->load(Yii::$app->getRequest()->post())) {
            throw new BadRequestHttpException('Can\'t load form.');
        }

        try {
            $form->create();
            $apiViewClass = $this->getApiViewClass();
            return new $apiViewClass();
        } catch (ValidationException $e) {
            return $form;
        }

        throw new ServerErrorHttpException('Can\'t delete item for unknown reason.');
    }
}
