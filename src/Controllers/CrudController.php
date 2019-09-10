<?php
declare(strict_types=1);

namespace Artica\Controllers;

use Artica\Forms\CrudForm;
use Yii;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;

/**
 * Class CrudController
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Controllers
 *
 */
abstract class CrudController extends Controller
{
    /**
     * Return Crud form object.
     *
     * @return \Artica\Forms\CrudForm
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    abstract protected function getCrudForm(): CrudForm;

    public function actionCreate()
    {
        $form = $this->getCrudForm();

        if (!$form->load(Yii::$app->getRequest()->post(), $form->formName())) {
            throw new BadRequestHttpException('Can\'t load form.');
        }

        $form->create();

        if ($form->getFirstErrors()) {
            return $form->getErrorSummary(true);
        }


    }
}
