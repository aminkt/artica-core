<?php
declare(strict_types=1);


namespace Artica\Controllers;

use aminkt\yii2\rest\utils\HttpCode;
use InvalidArgumentException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * Trait RestControllerTrait
 * Use this trait in your rest controller to handle auth and CORS.
 *
 * @property array $optionalAuthRoutes  Add action ids that do not need auth.
 *                                      Auth will available but not required in this action.
 * @property array $onlyAuthRoutes      Add action ids that need auth.
 * @property array $extraCorsHeaders    Additinal cors headers.
 *
 * @package rest\components
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
trait RestControllerTrait
{
    /**
     * @var bool See details {@link \yii\web\Controller::$enableCsrfValidation}.
     */
    public $enableCsrfValidation = false;

    /**
     * Create an error for api response.
     *
     * @param array|string $message
     * @param int          $code
     *
     * @return array
     */
    public function error($message, $code = 400)
    {
        $invalidResponseCodes = [
            HttpCode::OK,
            HttpCode::CREATED,
            HttpCode::ACCEPTED,
            HttpCode::NO_CONTENT,
        ];

        if (in_array($code, $invalidResponseCodes)) {
            throw new InvalidArgumentException('Status code should be an error status code. use success method instead.');
        }

        return static::message($message, $code);
    }

    /**
     * Create an message for api response.
     *
     * @param array|string $message
     * @param int          $code
     *
     * @return array
     */
    public static function message($message, $code)
    {
        Yii::$app->response->setStatusCode($code);
        return $message;
    }

    /**
     * Create an success message for api response.
     *
     * @param array|string $message
     * @param int          $code
     *
     * @return array
     */
    public function success($message, $code = HttpCode::OK)
    {
        if ($code >= 400) {
            throw new InvalidArgumentException('Status code should be a success status code. use error method instead.');
        }
        return static::message($message, $code);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors = $this->addContentNegotiatorBehavior($behaviors);
        $behaviors = $this->addAuthBehavior($behaviors);

        // re-add authentication filter
        $behaviors['authenticator']['optional'] = $this->getOptionalAuthRoutes();
        $behaviors['authenticator']['only'] = $this->getOnlyAuthRoutes();

        return $behaviors;
    }

    /**
     * Config negotiator behavior config.
     *
     * @param $behaviors
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function addContentNegotiatorBehavior($behaviors): array
    {
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON
            ],
        ];
        return $behaviors;
    }

    /**
     * Add auth behavior configs.
     *
     * @param $behaviors
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function addAuthBehavior($behaviors): array
    {
        unset($behaviors['authenticator']);

        // re-add authentication filter
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['options'],
            'optional' => ['*']
        ];

        return $behaviors;
    }

    /**
     * Return list of action ids that auth not required but allowed.
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getOptionalAuthRoutes(): array
    {
        if (property_exists($this, 'optionalAuthRoutes')) {
            return $this->optionalAuthRoutes;
        }

        return ['*'];
    }

    /**
     * Return list of action ids that auth required.
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getOnlyAuthRoutes(): array
    {
        if (property_exists($this, 'onlyAuthRoutes')) {
            return $this->onlyAuthRoutes;
        }

        return [];
    }

    /**
     * Handle options request.
     *
     * @param $action
     *
     * @return bool
     *
     * @throws \yii\base\ExitException
     * @throws \yii\web\BadRequestHttpException
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function beforeAction($action)
    {
        $cors = $this->prepareCors();
        Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Origin', $cors['origin']);
        Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Methods', $cors['method']);
        Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Headers', $cors['headers']);
        Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Credentials', 'true');
        Yii::$app->getResponse()->getHeaders()->set('Allow', $cors['method']);

        if (Yii::$app->getRequest()->isOptions) {
            Yii::$app->end();
        }

        return parent::beforeAction($action);
    }

    /**
     * Prepare cors data from request header.
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    private function prepareCors(): array
    {
        $origin = Yii::$app->getRequest()->getHeaders()->get('origin');
        $method = Yii::$app->getRequest()->getHeaders()->get('Access-Control-Request-Method');
        $headers = Yii::$app->getRequest()->getHeaders()->get('Access-Control-Request-Headers');
        $headers = array_unique(array_merge($headers ? explode(',', $headers) : [], $this->getExtraCorsHeaders()));
        $headers = implode(',', $headers);
        return [
            'origin' => $origin,
            'method' => $method,
            'headers' => $headers
        ];
    }

    /**
     * Return list of extra cors headers.
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getExtraCorsHeaders(): array
    {
        if (property_exists($this, 'extraCorsHeader')) {
            return $this->extraCorsHeaders;
        }

        return [
            'x-pagination-current-page',
            'x-pagination-page-count',
            'x-pagination-per-page',
            'x-pagination-total-count'
        ];
    }
}
