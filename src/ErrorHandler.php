<?php

namespace damidevelopment\apiutils;

use damidevelopment\apiutils\exceptions\MinimumAppVersionException;
use damidevelopment\apiutils\exceptions\ValidationException;
use Yii;
use yii\base\Exception;
use yii\base\UserException;
use yii\db\Exception as DbException;
use yii\helpers\Json;
use yii\web\HttpException;
use yii\web\Response;


/**
 * @Author: Jakub Hrášek
 * @Date:   2018-06-22 11:08:15
 */
class ErrorHandler extends \yii\web\ErrorHandler
{
    /**
     * Converts an exception into an array.
     * @param \Exception|\Error $exception the exception being converted
     * @return array the array representation of the exception.
     */
    protected function convertExceptionToArray($exception)
    {
        if (!YII_DEBUG && !$exception instanceof UserException && !$exception instanceof HttpException) {
            $exception = new HttpException(500, Yii::t('errors', 'An internal server error occurred'));
        }

        $array = [
            'errorName' => Yii::t('errors', ($exception instanceof Exception) ? $exception->getName() : 'Exception'),
            'message' => Yii::t('errors', $exception->getMessage()),
            'code' => $exception->getCode(),
        ];

        if ($exception instanceof HttpException) {
            $array['code'] = $exception->statusCode;
        }

        if ($exception instanceof ValidationException) {
            $array['errors'] = [];
            foreach ($exception->model->getErrors() as $name => $message) {
                $array['errors'][] = [
                    'field' => $name,
                    'messages' => $message,
                ];
            }
        }

        if ($exception instanceof MinimumAppVersionException) {
            $array['errorName'] = Yii::t('errors', 'Deprecated app version');
            $array['message'] = Yii::t('errors', 'App requires update. Click on restart button to run update.');
        }

        if (YII_DEBUG) {
            $array['type'] = get_class($exception);
            if (!$exception instanceof UserException) {
                $array['file'] = $exception->getFile();
                $array['line'] = $exception->getLine();
                $array['stack-trace'] = explode("\n", $exception->getTraceAsString());
                if ($exception instanceof DbException) {
                    $array['error-info'] = $exception->errorInfo;
                }
            }
        }

        if (($prev = $exception->getPrevious()) !== null) {
            $array['previous'] = $this->convertExceptionToArray($prev);
        }

        return $array;
    }

    protected function handleFallbackExceptionMessage($exception, $previousException)
    {
        if (Yii::$app->getResponse()->format !== Response::FORMAT_JSON) {
            parent::handleFallbackExceptionMessage($exception, $previousException);
        }

        $msg = 'An Error occurred while handling another error: ' . Yii::t('errors', $exception->getMessage()) . (string)$previousException;
        if (YII_DEBUG) {
            echo Json::encode([
                'errorName' => Yii::t('errors', ($exception instanceof Exception) ? $exception->getName() : 'Exception'),
                'message' => $msg,
                'code' => 500,
                'type' => 'Unknown'
            ]);
        } else {
            echo Json::encode([
                'errorName' => Yii::t('errors','Internal server error'),
                'message' => Yii::t('errors','Internal server error'),
                'code' => 500,
                'type' => 'Unknown'
            ]);
        }
        if (defined('HHVM_VERSION')) {
            flush();
        }
        error_log($msg);
        exit(1);
    }

}