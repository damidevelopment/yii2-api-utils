<?php

return [
    'components' => [
        'device' => [
            'class' => damidev\api\Device::class
        ]
    ],
    'modules' => [
        'api' => [
            'class' => damidev\api\ApiModule::class,
            // uncomment this, if identityClass for login is different in this module
            // 'identityClass' => app\models\User::class,

            // api versions
            'modules' => [
                'v1' => damidev\api\Module::class,
                'v2' => [
                    'class' => damidev\api\Module::class,
                    'urlRules' => [
                        // @see [[yii\web\UrlManager::$rules]]
                    ]
                ]
            ]
        ],
    ]
];
