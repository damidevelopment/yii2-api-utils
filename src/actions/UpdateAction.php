<?php
/**
 * @Author: Martin Štěpánek
 * @Date: 17/04/2019 11:05
 */

namespace damidevelopment\apiutils\actions;

use damidevelopment\apiutils\Resource;
use yii\base\Action;
use Yii;

class UpdateAction extends Action
{

    /**
     * @var string|object Resource class
     */
    public $resourceClass;

    public function run()
    {
        $post = Yii::$app->getRequest()->post();
        $resource = Yii::createObject($this->resourceClass);
        if (!$resource instanceof Resource) {
            throw new \InvalidArgumentException(
                Yii::t('errors', 'Resource class must be type of {resource}'), ['resource' => Resource::class]
            );
        }

        $resource->loadResource($post);
        if ($resource->validateResource()) {
            $resource->saveResource();
        }
        return $resource;
    }

}