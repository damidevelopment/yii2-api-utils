<?php

namespace damidevelopment\apiutils\controllers;

use damidevelopment\apiutils\actions\ErrorAction;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\Response;


/**
 * RestController is the base class for RESTful API controller classes.
 *
 * @author Jakub Hrášek
 */
class RestController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => $this->verbs(),
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => ErrorAction::class,
        ];
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
}