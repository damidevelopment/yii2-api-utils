<?php

namespace damidevelopment\apiutils;

/**
 * @author Jakub Hrášek
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
        foreach ($model->getErrors() as $name => $messages) {
            $result[] = [
                'field' => $name,
                'messages' => $messages,
            ];
        }

        return [
            'code' => 422,
            'name' => \Yii::t('errors', 'Data Validation Failed'),
            'message' => \Yii::t('errors', 'Data Validation Failed'),
            'errors' => $result,
        ];
    }
}
