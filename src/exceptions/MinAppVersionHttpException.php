<?php

namespace damidev\api\exceptions;

use Yii;
use yii\web\HttpException;

class MinAppVersionHttpException extends HttpException
{
    /**
     * Constructor.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct(412, $message, $code, $previous);
    }
}
