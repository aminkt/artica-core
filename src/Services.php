<?php
declare(strict_types=1);

namespace Artica;

use Artica\Lib\SMS\SMSInterface;
use Yii;
use yii\base\Security;
use yii\caching\CacheInterface;
use yii\mail\MailerInterface;
use yii\queue\amqp_interop\Queue;
use yii\queue\redis\Queue as RedisQueue;
use yii\redis\Cache;

/**
 * Trait Services
 * Base Artica services.
 */
trait Services
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
     * @return SMSInterface
     */
    public static function serviceSMS(): SMSInterface
    {
        return Yii::$app->get('sms');
    }
}