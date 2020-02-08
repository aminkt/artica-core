<?php

namespace Artica\ErrorHandler;

use Exception;
use Yii;

/**
 * Class ErrorLogger
 * @package Artica\ErrorHandler
 */
class ErrorLogger
{
    /**
     * Log exception in logger service to handle it later.
     * @param Exception $exception
     * @param bool $isWarning
     */
    public function logException(Exception $exception, bool $isWarning = false): void
    {
        if ($isWarning) {
            Yii::warning(json_encode($exception));
        } else {
            Yii::error(json_encode($exception));
        }
    }

    /**
     * Log a message in error logger to handle it later.
     * @param $message
     * @param $isWarning
     */
    public function logMessage($message, $isWarning = false): void
    {
        if ($isWarning) {
            Yii::warning(($message));
        } else {
            Yii::info(($message));
        }
    }
}