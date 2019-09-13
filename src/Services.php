<?php
declare(strict_types=1);

namespace Artica;

use Artica\Lib\SMS\SMSInterface;
use Yii;
use yii\base\Security;
use yii\caching\CacheInterface;
use yii\mail\MailerInterface;
use yii\queue\amqp_interop\Queue;
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
    public function serviceSecurity(): Security
    {
        return Yii::$app->getSecurity();
    }

    /**
     * @return CacheInterface
     */
    public function serviceCache(): CacheInterface
    {
        return Yii::$app->getCache();
    }

    /**
     * @return Cache
     */
    public function serviceRedisCache(): Cache
    {
        return Yii::$app->get('redisCache');
    }

    /**
     * @return Queue
     */
    public function serviceRabbitMQ(): Queue
    {
        return Yii::$app->get('amqQueue');
    }

    /**
     * @return MailerInterface
     */
    public function serviceEmail(): MailerInterface
    {
        return Yii::$app->getMailer();
    }

    /**
     * @return SMSInterface
     */
    public function serviceSMS(): SMSInterface
    {
        return Yii::$app->get('sms');
    }
}