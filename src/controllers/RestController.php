<?php

namespace damidevelopment\apiutils\controllers;

use damidevelopment\apiutils\actions\ErrorAction;
use yii\filters\VerbFilter;


/**
 * RestController is the base class for RESTful API controller classes.
 *
 * @author Jakub Hrášek
 */
class RestController extends \yii\web\Controller
{

    /**
     * @var array the configuration for creating authenticator
     */
    public $authenticator = [
        'class' => \yii\filters\auth\HttpHeaderAuth::class,
        'header' => 'Access-Token'
    ];

    public function behaviors()
    {
        return [
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => $this->verbs(),
            ],
            'authenticator' => $this->authenticator,
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