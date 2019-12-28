<?php
declare(strict_types=1);

namespace Artica\Form;

use Artica\Model\BaseModel;

/**
 * Class Form
 * Base class for Artica Form.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Form
 */
abstract class BaseForm extends BaseModel
{
    /** @var null|string $formName Form name. If null use class name to generate form name. */
    protected $formName = null;

    /**
     * @inheritDoc
     */
    public function load($data, $formName = null)
    {
        if ($formName === null) {
            $formName = $this->formName;
        }

        return parent::load($data, $formName === null ? '' : $formName);
    }
}
