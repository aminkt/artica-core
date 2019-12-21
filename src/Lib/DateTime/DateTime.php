<?php
declare(strict_types=1);

namespace Artica\Lib\DateTime;

use DateTime as BaseDateTime;
use DateTimeZone;
use yii\db\Expression;

/**
 * Class DateTime
 * Can be used as standard date time object.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Lib\DateTime
 */
class DateTime extends BaseDateTime
{
    const FORMAT_STANDARD = 'Y-m-d H:i:s';
    const PHP_FORMAT_STANDARD = 'php:'.self::FORMAT_STANDARD;

    public function __construct($time = 'now', DateTimeZone $timezone = null)
    {
        if ($time instanceof Expression and strtolower($time->expression) == 'now()') {
            $time = 'now';
        }

        parent::__construct($time, $timezone);
    }
}
