<?php

namespace damidev\api;

use Yii;
use yii\web\Application;
use yii\web\Response;
use yii\base\InvalidConfigException;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use damidev\api\Serializer;
use damidev\api\behaviors\VerbFilter;

/**
 * @author Jakub Hrášek
 */
class ApiModule extends Module
{
    /**
     * @var array the configuration for creating the serializer that formats the response data.
     */
    public $serializer = [
        'class' => Serializer::class
    ];

    /**
     * @var array the configuration for creating authenticator
     */
    public $authenticator = [
        'class' => \yii\filters\auth\HttpHeaderAuth::class,
        'header' => 'Access-Token'
    ];

    /**
     * @var string the class name of the [[identity]] object.
     */
    public $identityClass;

    /**
     * Indicator if API module was already bootstraped
     * @var boolean
     */
    private static $apiBootstrapped = false;


    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        // allow only one instance of API module
        if (self::$apiBootstrapped) {
            throw new InvalidConfigException('This module must be bootstrapped only once, use damidev\api\Module instead.');
        }

        self::$apiBootstrapped = true;

        $app->on(Application::EVENT_BEFORE_REQUEST, function ($event) {
            /** @var Application $app */
            $app = $event->sender;

            /** @var \yii\web\Request $request */
            $request = $app->getRequest();

            list($route, ) = $app->get('urlManager')->parseRequest($request);

            $id = $this->getUniqueId();

            // change app behavior only for requests to this module
            if (\yii\helpers\StringHelper::startsWith($route, $id) === false) {
                return;
            }

            // disable csrf cookie
            $request->enableCsrfCookie = false;

            // set web user stateless
            $webUser = $app->getUser();
            $webUser->enableAutoLogin = false;
            $webUser->enableSession = false;

            // change web user identity class for this module
            if ($this->identityClass !== null) {
                $webUser->identityClass = $this->identityClass;
            }

            // trace response data
            $app->getResponse()->on(Response::EVENT_AFTER_SEND, function ($event) {
                /** @var Response $response */
                $response = $event->sender;
                Yii::trace(Json::encode($response->data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE), 'API response payload');
            });
        });

        parent::bootstrap($app);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formatParam' => null,
                'languageParam' => null,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
                'languages' => $this->acceptLanguages()
            ],
            'verbFilter' => [
                'class' => VerbFilter::class,
                'controllerActions' => [$this, 'controllerVerbs'],
            ],
            'authenticator' => $this->authenticator,
            // TODO make it work
            // 'rateLimiter' => [
            //     'class' => RateLimiter::class,
            // ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        return $this->serializeData($result);
    }

    /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
     * @param  yii\base\Action $action
     * @return array the allowed HTTP verbs.
     */
    protected function controllerVerbs($action)
    {
        if (method_exists($action->controller, 'verbs')) {
            return $action->controller->verbs();
        }
        return [];
    }

    /**
     * List of acceptable languages for response
     * @return array
     */
    protected function acceptLanguages()
    {
        $params = Yii::$app->params['acceptLanguages'] ?? [Yii::$app->sourceLanguage];
        return array_unique(array_merge(['en-US', 'cs-CZ'], $params));
    }

    /**
     * Serializes the specified data.
     * The default implementation will create a serializer based on the configuration given by [[serializer]].
     * It then uses the serializer to serialize the given data.
     * @param mixed $data the data to be serialized
     * @return mixed the serialized data.
     */
    protected function serializeData($data)
    {
        return Yii::createObject($this->serializer)->serialize($data);
    }
}
