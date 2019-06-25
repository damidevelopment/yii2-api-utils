<?php

namespace damidevelopment\apiutils\behaviors;

use damidevelopment\apiutils\User;
use damidevelopment\apiutils\UserAgent;


/**
 * @Author: Jakub Hrášek
 * @Date:   2018-06-26 15:06:29
 */
class HttpHeaderAuth extends \yii\filters\auth\HttpHeaderAuth
{
    /**
     * @var string the HTTP header name
     */
    public $headerUserAgent = 'User-Agent';
    public $headerAccessToken = 'Access-Token';

    /**
     * {@inheritdoc}
     */
    public function authenticate($user, $request, $response)
    {
        $authHeader = $request->getHeaders()->get($this->headerUserAgent);
        $accessToken = $request->getHeaders()->get($this->headerAccessToken);
        if ($accessToken !== null) {
           // $identity = User::create(new UserAgent($authHeader), );
            $user->login($identity);
            return $identity;
        }
        $this->challenge($response);
        $this->handleFailure($response);
        return null;
    }
}