<?php
/**
 * @Author: Martin Štěpánek
 * @Date: 06/08/2018 11:09
 */

namespace damidevelopment\apiutils\exceptions;


use yii\web\HttpException;

class MinimumAppVersionException extends HttpException
{

    /**
     * Constructor.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = 'Precondition Failed', $code = 0, \Exception $previous = null)
    {
        $message = \Yii::t('errors', 'Precondition Failed');
        parent::__construct(412, $message, $code, $previous);
    }

    public function getName()
    {
        return \Yii::t('errors', 'Precondition Failed');
    }

}