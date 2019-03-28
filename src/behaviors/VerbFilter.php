<?php

namespace damidev\api\behaviors;

class VerbFilter extends \yii\filters\VerbFilter
{
    /**
     * Override Yii VerbFilter before every action
     * @var callable
     */
    public $controllerActions;

    /**
     * {@inheritdoc}
     */
    public function beforeAction($event)
    {
        /** @var yii\base\ActionEvent $event */

        if (is_callable($this->controllerActions)) {
            $this->actions = call_user_func($this->controllerActions, $event->action);
        }
        return parent::beforeAction($event);
    }
}
