<?php

namespace damidev\api\controllers;

use Yii;
use yii\filters\auth\HttpHeaderAuth;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\filters\RateLimiter;
use yii\web\Response;
use damidev\api\Serializer;

/**
 * RestController is the base class for RESTful API controller classes.
 *
 * For more info see [[\yii\rest\Controller]].
 *
 * @author Jakub Hrášek
 */
abstract class RestController extends \yii\web\Controller
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
        'class' => HttpHeaderAuth::class,
        'header' => 'Access-Token'
    ];

    /**
     * {@inheritdoc}
     */
    public $enableCsrfValidation = false;


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formatParam' => null,
                'languageParam' => '_lang',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
                'languages' => $this->acceptLanguages()
            ],
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => $this->verbs(),
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
     * @return array the allowed HTTP verbs.
     */
    protected function verbs()
    {
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
