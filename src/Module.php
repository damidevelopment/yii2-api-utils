<?php

namespace damidev\api;

use yii\base\BootstrapInterface;

/**
 * @author Jakub Hrášek
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules($this->buildUrlRules(), false);
    }

    /**
     * Returns list of rules for [[\yii\web\UrlManager]]
     * @return array
     */
    protected function buildUrlRules(): array
    {
        return [];
    }
}
