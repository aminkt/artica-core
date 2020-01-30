<?php

namespace Artica\ApiView;

use Artica\Entity\Entity;
use Artica\Exception\View\ViewNotFoundException;

/**
 * Class CrudApiView
 * An view file to separate view presentation from model or controller.
 * This class can be used in crud controllers to provide standard output for every crud action.
 *
 * @package Artica\ApiView
 */
abstract class CrudApiView extends ControllerApiView implements EntityApiViewInterface
{
    use EntityApiViewTrait;

    const SCENARIOS = [
        'index',
        'create',
        'update',
        'delete',
        'view'
    ];

    /**
     * CrudApiView constructor.
     * @param Entity|null $entity
     * @throws ViewNotFoundException When crud used for unsupported methpd.
     */
    public function __construct(?Entity $entity = null)
    {
        parent::__construct();
        $this->entity = $entity;
        if (!in_array($this->action, self::SCENARIOS)) {
            $actionId = $this->controller . '/' . $this->action;
            throw new ViewNotFoundException('View ' . get_called_class() . ' can not handle action ' . $actionId);
        }
    }

    /**
     * Return index action response.
     * @return array
     */
    abstract function indexResponse(): array;

    /**
     * Return view action response.
     * @return array
     */
    abstract function viewResponse(): array;

    /**
     * Return create action response.
     * @return array
     */
    function createResponse(): array
    {
        return $this->viewResponse();
    }

    /**
     * Return update action response.
     * @return array
     */
    function updateResponse(): array
    {
        return $this->viewResponse();
    }

    /**
     * Return delete action response.
     * @return array
     */
    function deleteResponse(): array
    {
        return ['Item deleted successfully'];
    }
}
