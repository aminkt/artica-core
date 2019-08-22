<?php

namespace Artica\Lib\Oauth\Actions;

use Exception;
use HangApp\AccountService\Lib\Oauth\OauthServer;
use http\Exception\RuntimeException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class TokenAction
 * Handle Token request action.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package HangApp\AccountService\Lib\Oauth
 */
class TokenAction extends OauthAction
{
    /**
     * Handle Token request action.
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function run()
    {
        /** @var OauthServer $server */
        $server = Yii::$container->get(OauthServer::class);
        if (empty($server)) {
            throw new RuntimeException('Oauth server is not loaded.');
        }

        try {
            return $server->getSever()->respondToAccessTokenRequest(
                $this->serverRequest,
                $this->serverResponse
            );
        } catch (OAuthServerException $e) {
            return $e->generateHttpResponse($this->serverResponse);
        } catch (InvalidConfigException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
