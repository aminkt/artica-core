<?php
declare(strict_types=1);

namespace Artica\Controllers;

use Artica\Entities\Entity;
use Artica\Forms\CrudForm;
use Yii;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class CrudController
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Controllers
 *
 * @property CrudForm $crudForm
 */
abstract class CrudController extends Controller
{
    /**
     * Return Crud form object.
     *
     * @return CrudForm
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    abstract protected function getCrudForm(): CrudForm;

    /**
     * View an item using id.
     *
     * @param mixed $id Item id.
     */
    public function actionView($id)
    {

    }

    /**
     * Return list of items.
     */
    public function actionIndex()
    {

    }

    /**
     * Create a new item using crudform.
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function actionCreate()
    {
        $form = $this->getCrudForm();

        if (!$form->load(Yii::$app->getRequest()->post(), $form->formName())) {
            throw new BadRequestHttpException('Can\'t load form.');
        }

        $entity = $form->create();

        if ($form->hasErrors()) {
            return $form->getErrorSummary(true);
        }

        return $entity;
    }

    /**
     * Update entity using crud form definition.
     *
     * @param mixed $id Entity id.
     *
     * @return array|Entity|null
     */
    public function actionUpdate($id)
    {
        $form = $this->getCrudForm();

        if (!$form->load(Yii::$app->getRequest()->post(), $form->formName())) {
            throw new BadRequestHttpException('Can\'t load form.');
        }

        $entity = $form->update($id);

        if ($form->hasErrors()) {
            return $form->getErrorSummary(true);
        }

        return $entity;
    }

    /**
     * Delete an item using Crud form.
     *
     * @param mixed $id Item id.
     *
     * @return array
     */
    public function actionDelete($id)
    {
        $form = $this->getCrudForm();

        if (!$form->load(Yii::$app->getRequest()->post(), $form->formName())) {
            throw new BadRequestHttpException('Can\'t load form.');
        }

        if ($form->delete($id)) {
            return ['Item deleted'];
        }

        if ($form->hasErrors()) {
            return $form->getErrorSummary(true);
        }

        throw new ServerErrorHttpException('Can\'t delete item for unknown reason.');
    }
}
