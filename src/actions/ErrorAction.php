<?php
/**
 * @Author: Martin Štěpánek
 * @Date: 17/04/2019 11:05
 */

namespace damidevelopment\apiutils\actions;

use damidevelopment\apiutils\Resource;
use yii\base\Action;
use Yii;
use yii\web\Response;
use yii\base\Exception;


class ErrorAction extends \yii\web\ErrorAction
{

    public function run()
    {
        $response = Yii::$app->getResponse();
        if ($response->format !== Response::FORMAT_JSON) {
            return parent::run();
        }
        return [
            'errorName' => Yii::t('errors', ($this->exception instanceof Exception) ? $this->exception->getName() : 'Exception'),
            'message' =>  Yii::t('error', $this->exception->getMessage()),
            'code' => $this->getExceptionCode(),
            'type' => 'Unknown'
        ];
    }

}