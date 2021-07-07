<?php

namespace damidev\api\behaviors;

use yii\base\ActionFilter;

class MinAppVersion extends ActionFilter
{
    /**
     * Minimum app version value for Client.
     * @var string
     */
    public $appVersion;

    /**
     * @var damidev\api\Device
     */
    private $device;


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->device = Yii::$app->get('device', false);
    }

    /**
     * @inheritdoc
     */
    public function beforeAction()
    {
        if (!$this->device) {
            return true;
        }

        $appVersion = explode('.', $this->appVersion);
        $deviceVersion = explode('.', $this->device->appVersion);

        foreach ($deviceVersion as $index => $value) {
            $minVersion = $appVersion[$index] ?? 0;

            if ($index === count($deviceVersion) - 1 && $minVersion > $value) {
                return false;
            }
            elseif ($minVersion >= $value) {
                return false;
            }
        }

        return true;
    }

    protected function handleError()
    {
        //
    }
}
