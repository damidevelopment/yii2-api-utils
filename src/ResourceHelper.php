<?php
/**
 * @Author: Martin Štěpánek
 * @Date: 10/04/2019 15:56
 */

namespace damidevelopment\apiutils;

use Yii;

class ResourceHelper
{

    public static function createResources($resourceClass, array $models): array
    {
        $resources = [];
        foreach ($models as $model) {
            $resource = Yii::createObject($resourceClass);
            if (!$resource instanceof Resource) {
                throw new \InvalidArgumentException(
                    Yii::t('errors', 'Resource class must be type of {resource}'), ['resource' => Resource::class]
                );
            }

            $resource->setModel($model);
            $resources[] = $resource;
        }
        return $resources;
    }

}