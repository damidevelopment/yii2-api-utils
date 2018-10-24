<?php

namespace damidev\api;


/**
 * @Author: Jakub Hrášek
 */
class Serializer extends \yii\rest\Serializer
{
    /**
     * @inheritdoc
     */
    public $collectionEnvelope = 'items';


    /**
     * Serializes the validation errors in a model.
     * @param  \yii\base\Model $model
     * @return array the array representation of the errors
     */
    protected function serializeModelErrors($model)
    {
        $this->response->setStatusCode(422, 'Data Validation Failed');

        $result = [];
        foreach ($model->getErrors() as $name => $message) {
            $result[] = [
                'field' => $name,
                'messages' => $message,
            ];
        }

        return [
            'code' => 422,
            'name' => 'Data Validation Failed',
            'message' => 'Data Validation Failed',
            'errors' => $result
        ];
    }
}
