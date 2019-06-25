<?php

return [
    'components' => [
        'device' => [
            'class' => damidevelopment\apiutils\Device::class
        ]
    ],
    'modules' => [
        'api' => [
            'class' => damidevelopment\apiutils\ApiModule::class,
            // uncomment this, if identityClass for login is different in this module
            // 'identityClass' => app\models\User::class,

            // api versions
            'modules' => [
                'v1' => damidevelopment\apiutils\Module::class,
                'v2' => [
                    'class' => damidevelopment\apiutils\Module::class,
                    'urlRules' => [
                        // @see [[yii\web\UrlManager::$rules]]
                    ]
                ]
            ]
        ],
    ]
];
