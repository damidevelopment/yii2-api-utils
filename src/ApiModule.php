<?php

namespace damidevelopment\apiutils;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;
use yii\base\ExitException;
use yii\web\Application;
use yii\web\Response;
use yii\filters\ContentNegotiator;


/**
 * @author Jakub Hrášek
 */
class ApiModule extends Module implements BootstrapInterface
{
    /**
     * @var array the configuration for creating the serializer that formats the response data.
     */
    public $serializer = [
        'class' => Serializer::class
    ];

    /**
     * @var string the class name of the [[identity]] object.
     */
    public $identityClass;

    public $acceptLanguages = [];

    public $errorHandler = ErrorHandler::class;

    /**
     * @var string
     */
    public $requestIdHeader = 'X-Request-Id';

    /**
     * @var bool
     */
    private $_isValidRequest;

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        $app->on(Application::EVENT_BEFORE_REQUEST, function ($event) {
            /** @var Application $app */
            $app = $event->sender;

            // change app behavior only for requests to this module
            if (!$this->isValidRequest($app)) {
                return;
            }

            $errorHandler = Yii::createObject($this->errorHandler);
            $app->set('errorHandler', $errorHandler);
            $errorHandler->register();

            /** @var \yii\web\Request $request */
            $request = $app->getRequest();

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
                $data = \yii\helpers\Json::encode($response->data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
                Yii::getLogger()->log($data, \yii\log\Logger::LEVEL_TRACE, 'API response payload');
            });
        });

        $this->handleMultiRequest($app);

        foreach ($this->getModules() as $name => $module) {
            if(in_array(BootstrapInterface::class, class_implements($module))){
                $this->getModule($name)->bootstrap($app);
            }
        }

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
                'languages' => $this->acceptLanguages,
            ],
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
     * Validation for request event.
     * @param  Application $app
     * @return boolean
     */
    public function isValidRequest(Application $app): bool
    {
        if ($this->_isValidRequest === null) {
            /** @var \yii\web\Request $request */
            $request = $app->getRequest();

            list($route, ) = $app->get('urlManager')->parseRequest($request);

            $id = $this->getUniqueId();

            // change app behavior only for requests to this module
            $this->_isValidRequest = \yii\helpers\StringHelper::startsWith($route, $id);
        }
        return $this->_isValidRequest;
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

    /**
     * Handle OkHttp silent retry calls.
     *
     * @see https://medium.com/inloopx/okhttp-is-quietly-retrying-requests-is-your-api-ready-19489ef35ace
     *
     * @param Application $app
     * @return void
     */
    private function handleMultiRequest(Application $app)
    {
        /** @var yii\caching\CacheInterface $cache */
        $cache = $app->getCache();

        $app->on(Application::EVENT_BEFORE_REQUEST, function ($event) use ($cache) {
            /** @var Application $app */
            $app = $event->sender;

            if (!$this->isValidRequest($app)) {
                return;
            }

            $headers = $app->getRequest()->getHeaders();

            if (!$headers->has($this->requestIdHeader)) {
                return;
            }

            $this->requestId = $headers->get($this->requestIdHeader);

            // when cached request exists, skip aplication request handling
            if ($cache->exists($this->requestId)) {
                throw new ExitException();
            }

            // allow response cache on after send event
            $app->getResponse()->on(Response::EVENT_AFTER_SEND, function ($event) use ($cache) {
                /** @var Response $response */
                $response = $event->sender;

                // don't cache stream response
                if ($response->stream !== null) {
                    return;
                }

                $cacheResponse = new \stdClass();
                $cacheResponse->headers = $response->getHeaders()->toArray();
                $cacheResponse->cookies = $response->getCookies()->toArray();
                $cacheResponse->statusCode = $response->getStatusCode();
                $cacheResponse->statusText = $response->statusText;
                $cacheResponse->content = $response->content;

                $cache->set($this->requestId, $cacheResponse, 30);
            });
        });

        $app->on(Application::EVENT_AFTER_REQUEST, function ($event) use ($cache) {
            if (!$this->requestId || !$cache->exists($this->requestId)) {
                return;
            }

            $response = $app->getResponse();
            $cacheResponse = $cache->get($this->requestId);

            $response->getHeaders()->fromArray($cacheResponse->headers);
            $response->getCookies()->fromArray($cacheResponse->cookies);
            $response->setStatusCode($cacheResponse->statusCode, $cacheResponse->statusText);
            $response->content = $cacheResponse->content;
        });
    }
}
