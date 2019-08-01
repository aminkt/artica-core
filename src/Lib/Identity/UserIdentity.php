<?php

namespace aminkt\yii2\oauth2\jwt;

use yii\base\InvalidArgumentException;
use Firebase\JWT\JWT;

trait UserIdentity
{
    /**
     * Finds an identity by the given token.
     *
     * @param mixed $token the token to be looked for
     *
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     *
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if ($type == 'yii\filters\auth\HttpBearerAuth') {
            $payload = (array)JWT::decode($token, self::getEncryptKey(), [self::getEncryptAlgorithm()]);
            $id = $payload['data']->userId;
            return self::findOne($id);
        }
    }
    /**
     * Return algorithm that to use encrypt and decypt token.
     *
     * @return string
     */
    protected static function getEncryptAlgorithm()
    {
        return 'HS256';
    }
    /**
     * Return an encrypt key to use in encode and decode JWT token.
     * This token should be unique in both decode and encode processes.
     *
     * @return string       Encrypt key.
     *
     * @throws \BadMethodCallException  This methdo should implement in calss that implemented IdentityInterface.
     */
    protected static function getEncryptKey()
    {
        throw new \BadMethodCallException("Please implement getEncryptKey() in " . static::class . '.');
    }
    /**
     * Genereate a new JWT token.
     */
    public function generateToken($payload = null)
    {
        if (!$payload) {
            $payload = $this->payloadCreator();
        }
        if (!isset($payload['iat']) or
            !isset($payload['data']) or
            !isset($payload['data']['userId'])) {
            throw new InvalidArgumentException("Payload is not valid.");
        }
        $token = JWT::encode($payload, self::getEncryptKey(), self::getEncryptAlgorithm());
        return $token;
    }
    /**
     * Create a payload for JWT token.
     *
     * @return array    Payload array.
     */
    public function payloadCreator()
    {
        $payload = [
            'iat' => time(),         // Issued at: time when the token was generated
            'jti' => $this->getId().'_'.time(),          // Json Token Id: an unique identifier for the token
            'iss' => \Yii::$app->getUrlManager()->getHostInfo(),       // Issuer
            'nbf' => time(),        // Not before
            'exp' => time() + 3600 * 2,           // Expire
            'data' => [                  // Data related to the signer user
                'userId' => $this->getId()
            ]
        ];
        return $payload;
    }
    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     *
     * @return string a key that is used to check the validity of a given identity ID.
     *
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return '';
    }
    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     *
     * @param string $authKey the given auth key
     *
     * @return bool whether the given auth key is valid.
     *
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }
}