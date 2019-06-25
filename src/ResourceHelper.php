<?php
/**
 * @Author: Martin Štěpánek
 * @Date: 10/04/2019 15:56
 */

namespace damidevelopment\apiutils;


class ResourceHelper
{

    public static function createResources($resourceClass, array $models): array
    {
        $resources = [];
        foreach ($models as $model) {
            $resource = \Yii::createObject($resourceClass);
            if (!$resource instanceof Resource) {
                throw new \InvalidArgumentException('Resource class must be instance of ' . Resource::class);
            }

            $resource->setModel($model);
            $resources[] = $resource;
        }
        return $resources;
    }

}