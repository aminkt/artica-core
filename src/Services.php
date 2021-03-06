<?php
declare(strict_types=1);

namespace Artica;

use Artica\ErrorHandler\ErrorLogger;
use Artica\Lib\DateTime\DateTime;
use Artica\Lib\SMS\SMSInterface;
use DateTimeZone;
use Yii;
use yii\base\Security;
use yii\base\View;
use yii\caching\CacheInterface;
use yii\i18n\Formatter;
use yii\log\Logger;
use yii\mail\MailerInterface;
use yii\queue\amqp_interop\Queue;
use yii\queue\redis\Queue as RedisQueue;
use yii\redis\Cache;

/**
 * Class Services
 * Base Artica services.
 */
abstract class Services
{
    /**
     * @return Security
     */
    public static function serviceSecurity(): Security
    {
        return Yii::$app->getSecurity();
    }

    /**
     * @return CacheInterface
     */
    public static function serviceCache(): CacheInterface
    {
        return Yii::$app->getCache();
    }

    /**
     * @return Cache
     */
    public static function serviceRedisCache(): Cache
    {
        return Yii::$app->get('redisCache');
    }

    /**
     * @return Queue
     */
    public static function serviceRabbitMQ(): Queue
    {
        return Yii::$app->get('amqQueue');
    }

    /**
     * @return RedisQueue
     */
    public static function serviceQueue(): RedisQueue
    {
        return Yii::$app->get('redisQueue');
    }

    /**
     * @return MailerInterface
     */
    public static function serviceEmail(): MailerInterface
    {
        return Yii::$app->getMailer();
    }

    /**
     * @return Formatter
     */
    public static function serviceFormatter(): Formatter
    {
        return Yii::$app->getFormatter();
    }

    /**
     * @param string            $time
     * @param DateTimeZone|null $timezone
     *
     * @return DateTime
     */
    public static function serviceDateTime($time = 'now', DateTimeZone $timezone = null): DateTime
    {
        if ($timezone === null) {
            $timezone = new DateTimeZone('Asia/Tehran');
        }
        return new DateTime($time, $timezone);
    }

    /**
     * @return ErrorLogger
     */
    public static function serviceErrorLogger(): ErrorLogger
    {
        $logger = new ErrorLogger();
        return $logger;
    }

    /**
     * @return Logger
     */
    public static function serviceLog(): Logger
    {
        return Yii::getLogger();
    }

    /**
     * @return \yii\base\View
     */
    public static function serviceHtmlRender(): View
    {
        return Yii::$app->getView();
    }
}