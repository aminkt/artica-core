<?php
declare(strict_types=1);


namespace Artica\Lib\SMS;

/**
 * Interface SMSInterface
 * Interface to use send sms message.
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Lib\SMS
 */
interface SMSInterface
{
    /**
     * Set Sms message.
     * @param string $message
     * @return SMSInterface
     */
    public function setMessage(string $message): SMSInterface;

    /**
     * Set receiver phone numbers.
     *
     * @param array $phone every row contain one number.
     *
     * @return SMSInterface
     */
    public function setPhones(array $phone): SMSInterface;

    /**
     * Send message to phones.
     *
     * @param bool $async   Send async or not. Async will use queue to send messages.
     *
     * @return bool
     */
    public function send(bool $async = false): bool;
}