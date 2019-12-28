<?php


namespace Artica\Exception\Model;

use Artica\Entity\Entity;
use Artica\Model\BaseModel;

/**
 * Trait ValidationExceptionTrait
 * Implementation of Validation exception to use in exception classes like EntityValidationException and so on.
 *
 * @package Artica\Exception\Model
 */
trait ValidationExceptionTrait
{
    /**
     * Return entity errors.
     *
     * @param string|null $attribute
     *
     * @return array
     */
    public function getErrors(?string $attribute = null)
    {
        /** @var BaseModel|Entity $model */
        $model = $this->getModel();
        return $model->getErrors($attribute);
    }

    /**
     * Provide entity error message.
     *
     * @return string
     */
    protected function provideErrorMessage(): string
    {
        /** @var BaseModel|Entity $model */
        $model = $this->getModel();
        $message = '';
        if ($model->hasErrors()) {
            $message .= 'Validation Errors:';
            foreach ($model->getErrors() as $attribute => $errors) {
                $message .= ' #'.$attribute .':';
                foreach ($model->getErrors($attribute) as $error) {
                    $message .= ' -' . $error;
                }
            }
        }
        return $message;
    }
}