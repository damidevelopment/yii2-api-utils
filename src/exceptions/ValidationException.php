<?php

namespace damidevelopment\apiutils\exceptions;

use yii\base\Model;
use yii\web\HttpException;


/**
 * @Author: Jakub Hrášek
 * @Date:   2018-06-22 11:37:58
 */
class ValidationException extends HttpException
{
    /**
     * @var Model model with errors
     */
    public $model;

    /**
     * Constructor.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct(Model $model, $message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct(422, $message, $code, $previous);
        $this->model = $model;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return \Yii::t('errors', 'Data Validation Failed');
    }
}