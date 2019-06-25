<?php

namespace damidevelopment\apiutils\controllers;

use damidevelopment\apiutils\behaviors\HttpHeaderAuth;
use damidevelopment\apiutils\Serializer;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use Yii;


/**
 * RestController is the base class for RESTful API controller classes.
 *
 * @author Jakub Hrášek
 */
class RestController extends \yii\web\Controller
{

    /**
     * @var string|array the configuration for creating the serializer that formats the response data.
     */
    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'items'
    ];

    /**
     * @var array the configuration for creating authenticator
     */
    public $authenticator = [
        'class' => HttpHeaderAuth::class,
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
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'text/plain' => Response::FORMAT_RAW,
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => $this->verbs(),
            ],
            'authenticator' => $this->authenticator,
            'rateLimiter' => [
                'class' => RateLimiter::class,
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        $lang = $this->getRequest()->getHeaders()->get('Accept-Language');
        if ($lang) {
            Yii::$app->language = $lang;
        } else {
            Yii::$app->language = 'en-US';
        }
        return parent::beforeAction($action);
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