<?php

namespace damidevelopment\apiutils\controllers;

use Yii;
use yii\filters\auth\HttpHeaderAuth;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\filters\RateLimiter;
use yii\web\Response;
use damidevelopment\apiutils\Serializer;

/**
 * RestController is the base class for RESTful API controller classes.
 *
 * @author Jakub Hrášek
 */
abstract class RestController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public $enableCsrfValidation = false;


    /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
     * @return array the allowed HTTP verbs.
     */
    public function verbs()
    {
        return [];
    }
}
