<?php

namespace Artica\Lib\Oauth;

use DateInterval;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use yii\base\Component;

/**
 * Class OauthServer
 * Initialize and handle oauth server.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package HangApp\AccountService\Lib\Oauth
 *
 *
 * @property-read  \League\OAuth2\Server\AuthorizationServer $sever
 */
class OauthServer extends Component
{
    public $userModelClass;
    public $clientModelClass;
    public $accessTokenModelClass;
    public $authCodeModelClass;
    public $refreshTokenModelClass;
    public $scopeModelClass;
    public $jwtPrivateKeyPath;
    public $jwtPublicKeyPath;
    public $encryptionKey;

    /** @var \League\OAuth2\Server\AuthorizationServer|null */
    private $_server;

    public function init()
    {
        parent::init();
        $this->initServer();
    }

    /**
     * Initialize oauth server.
     *
     * @return void
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \Exception
     */
    private function initServer()
    {
        $clientModel = new $this->clientModelClass();
        $accessTokenModel = new $this->accessTokenModelClass();
        $scopeModel = new $this->$scopeRepository();
        $userModel = new $this->userModelClass();
        $refreshTokenModel = new $this->refreshTokenModelClass();

        $this->_server = new AuthorizationServer(
            $clientModel,
            $accessTokenModel,
            $scopeModel,
            $this->jwtPrivateKeyPath,
            $this->encryptionKey
        );

        $grant = new PasswordGrant(
            $userModel,
            $refreshTokenModel
        );

        $grant->setRefreshTokenTTL(new DateInterval('P1M')); // refresh tokens will expire after 1 month

        // Enable the password grant on the server
        $this->_server->enableGrantType(
            $grant,
            new DateInterval('PT1H') // access tokens will expire after 1 hour
        );
    }

    /**
     * Return Server object.
     *
     * @return \League\OAuth2\Server\AuthorizationServer
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \Exception
     */
    public function getSever(): AuthorizationServer
    {
        if ($this->_server) {
            return $this->_server;
        }

        $this->initServer();
        return $this->_server;
    }
}