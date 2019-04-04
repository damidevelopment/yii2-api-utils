<?php

namespace damidev\api;

use yii\base\BaseObject;
use yii\web\Request;

class Device extends BaseObject
{
    /**
     * @var string
     */
    protected $appName;

    /**
     * @var string
     */
    protected $appVersion;

    /**
     * @var string
     */
    protected $buildNumber;

    /**
     * @var string
     */
    protected $bundleId;

    /**
     * @var string
     */
    protected $osName;

    /**
     * @var string
     */
    protected $osVersion;

    /**
     * @var string
     */
    protected $deviceName;

    /**
     * @var string
     */
    protected $deviceId;

    /**
     * @var Request
     */
    private $request;


    /**
     * Device constructor
     *
     * @param Request $request
     * @param array   $config  name-value pairs that will be used to initialize the object properties
     */
    public function __construct(Request $request, $config = [])
    {
        $this->request = $request;
        parent::__construct($config);
    }

    /**
     * User-Agent must look like this:
     * {appName}/{appVersion}/{buildNumber}/{bundleID} {OS}/{OSversion} ({deviceType};{deviceID})
     *
     * @inheritdoc
     */
    public function init()
    {
        $pattern = "~^([\w\W\d ]+)/(v?[0-9.-_]+)/(\d+)/([\w.]+) ([\w]+)/(v?[0-9.-_]+) \(([\w]+);?([\w]+)?\)$~";
        $userAgent = $this->request->getHeaders()->get('User-Agent');

        list(
            $this->appName,
            $this->appVersion,
            $this->buildNumber,
            $this->bundleId,
            $this->osName,
            $this->osVersion,
            $this->deviceName,
            $this->deviceId
        ) = preg_split($pattern, $userAgent, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
    }

    /**
     * Returns if device is Android
     * @return boolean
     */
    public function isAndroid(): bool
    {
        return $this->osName === 'Android';
    }

    /**
     * Returns if device is iOS
     * @return boolean
     */
    public function isIOS(): bool
    {
        return $this->osName === 'iOS';
    }

    /**
     * Returns appName
     * @return string
     */
    public function getAppName()
    {
        return $this->appName;
    }

    /**
     * Returns appVersion
     * @return string
     */
    public function getAppVersion()
    {
        return $this->appVersion;
    }

    /**
     * Returns buildNumber
     * @return string
     */
    public function getBuildNumber()
    {
        return $this->buildNumber;
    }

    /**
     * Returns bundleId
     * @return string
     */
    public function getBundleId()
    {
        return $this->bundleId;
    }

    /**
     * Returns osName
     * @return string
     */
    public function getOsName()
    {
        return $this->osName;
    }

    /**
     * Returns osVersion
     * @return string
     */
    public function getOsVersion()
    {
        return $this->osVersion;
    }

    /**
     * Returns deviceName
     * @return string
     */
    public function getDeviceName()
    {
        return $this->deviceName;
    }

    /**
     * Returns deviceId
     * @return string
     */
    public function getDeviceId()
    {
        return $this->deviceId;
    }
}
