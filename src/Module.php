<?php

namespace damidev\api;

use yii\base\BootstrapInterface;

/**
 * @author Jakub Hrášek
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @var array
     */
    private $rules = [];


    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules($this->rules, false);
    }

    /**
     * Returns list of rules for [[\yii\web\UrlManager]]
     * @param  array $rules
     * @return self
     */
    public function setUrlRules(array $rules): self
    {
        $this->rules = $rules;
        return $this;
    }
}
