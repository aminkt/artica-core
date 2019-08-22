<?php

namespace Artica\Lib\Oauth\Actions;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Yii;
use yii\rest\Action;

/**
 * Class OauthAction
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package HangApp\AccountService\Lib\Oauth\Actions
 *
 *
 * @property-read  \GuzzleHttp\Psr7\Response      $serverResponse
 * @property-read  \GuzzleHttp\Psr7\ServerRequest $serverRequest
 */
class OauthAction extends Action
{
    private $_response;
    private $_request;

    /**
     * Return server response.
     *
     * @return \GuzzleHttp\Psr7\Response
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function getServerResponse(): Response
    {
        if (empty($this->_response)) {
            $this->_response = new Response(
                Yii::$app->getResponse()->getStatusCode(),
                Yii::$app->getResponse()->getHeaders()->toArray()
            );
        }

        return $this->_response;
    }

    /**
     * Return server request.
     *
     * @return \GuzzleHttp\Psr7\ServerRequest
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \yii\base\InvalidConfigException
     */
    protected function getServerRequest(): ServerRequest
    {
        if (empty($this->_request)) {
            $this->_request = new ServerRequest(
                Yii::$app->getRequest()->getMethod(),
                Yii::$app->getRequest()->getUrl(),
                Yii::$app->getRequest()->getHeaders()->toArray(),
            );
        }

        return $this->_request;
    }
}
