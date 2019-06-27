<?php

return [
    'components' => [
        'userAgent' => \damidevelopment\apiutils\UserAgent::class
    ],
    'modules' => [
        'api' => [
            'class' => damidevelopment\apiutils\ApiModule::class,
            // uncomment this, if identityClass for login is different in this module
            // 'identityClass' => app\models\User::class,
            // 'errorHandler' => CustomErrorHandler::class,
            'acceptLanguages' => ['en-US', 'cs-CZ'],

            // api versions
            'modules' => [
                'v1' => app\api\v1\Module::class,
                'v2' => app\api\v2\Module::class,
            ]
        ],
    ]
];
