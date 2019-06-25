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
    public $header = 'Access-Token';

    /**
     * {@inheritdoc}
     */
    public function authenticate($user, $request, $response)
    {
        $userAgent = $request->getHeaders()->get($this->headerUserAgent);
        $accessToken = $request->getHeaders()->get($this->header);
        if ($userAgent) {
            $identity = User::create(new UserAgent($userAgent));
            $identity->accessToken = $accessToken;
            $user->login($identity);
            return $identity;
        }
        $this->challenge($response);
        $this->handleFailure($response);
        return null;
    }
}