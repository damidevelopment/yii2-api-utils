<?php

namespace damidev\api;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\helpers\StringHelper;
use yii\helpers\Json;
use yii\web\Response;


/**
 * @Author: Jakub Hrášek
 */
class Module extends \yii\base\Module implements BootstrapInterface
{

    /**
     * @var string the class name of the [[identity]] object.
     */
    public $identityClass;


    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->on(Application::EVENT_BEFORE_REQUEST, function ($event) {
            /** @var Application $app */
            $app = $event->sender;

            /** @var \yii\web\Request $request */
            $request = $app->getRequest();

            list($route, ) = $app->get('urlManager')->parseRequest($request);

            $id = $this->getUniqueId();

            // change app behavior only for requests to this module
            if (StringHelper::startsWith($route, $id) === false) {
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
    }

}