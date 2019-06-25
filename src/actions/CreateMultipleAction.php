<?php
/**
 * @Author: Martin Štěpánek
 * @Date: 17/04/2019 11:05
 */

namespace damidevelopment\apiutils\actions;

use damidevelopment\apiutils\Resource;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use Yii;

class CreateMultipleAction extends Action
{

    /**
     * @var string|object Resource class
     */
    public $resourceClass;

    /**
     * @var callable Action called before resources are save
     */
    public $beforeSave;

    /**
     * @var string Envelope variable in post
     */
    public $postVariable = 'data';

    public function run()
    {
        $post = Yii::$app->getRequest()->post($this->postVariable);
        if(!$post){
            throw new BadRequestHttpException('Items need to be sent in data attribute');
        }
        $resources = [];
        foreach ($post as $row) {
            $resource = Yii::createObject($this->resourceClass);
            if (!$resource instanceof Resource) {
                throw new \InvalidArgumentException('Resource class must be type of ' . Resource::class);
            }

            $resource->loadResource($row);
            if (!$resource->validateResource()) {
                return $resource;
            }
            $resources[] = $resource;
        }

        if (is_callable($this->beforeSave)) {
            $action = $this->beforeSave;
            $action();
        }

        foreach ($resources as $resource) {
            /** @var Resource $resource */
            $resource->saveResource();
        }

        return new ActiveDataProvider([
            'models' => $resources,
            'pagination' => false,
        ]);
    }

}