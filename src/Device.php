<?php

namespace damidev\api;

use yii\base\BaseObject;
use yii\web\Request;

class Device extends BaseObject
{
    /**
     * @var string
     */
    public $appName;

    /**
     * @var string
     */
    public $appVersion;

    /**
     * @var string
     */
    public $buildNumber;

    /**
     * @var string
     */
    public $bundleId;

    /**
     * @var string
     */
    public $osName;

    /**
     * @var string
     */
    public $osVersion;

    /**
     * @var string
     */
    public $deviceName;

    /**
     * @var string
     */
    public $deviceId;

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
        $headers = $this->request->getHeaders();

        $this->deviceId = $headers->get('X-Device-Id');

        $userAgent = $headers->get('User-Agent');

        // detect Dami mobile app
        $pattern = '~^([\w\W\d ]+)/(v?[0-9.-_]+)/(\d+)/([\w.]+) ([\w]+)/(v?[0-9.-_]+) \(([\w]+\))$~';
        if (preg_match($pattern, $userAgent)) {
            list(
                $this->appName,
                $this->appVersion,
                $this->buildNumber,
                $this->bundleId,
                $this->osName,
                $this->osVersion,
                $this->deviceName
            ) = preg_split($pattern, $userAgent, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
        }
        else {
            // detect mobile device
            if (preg_match('~Android|iPhone|iPad|iPod|webOS|BlackBerry|Windows Phone~', $userAgent, $matches)) {
                $this->osName = $matches[0];
                // TODO detect osVersion
            }
            // detect desktop device
            elseif (preg_match('~Macintosh|Linux|Windows~', $userAgent, $matches)) {
                $this->osName = $matches[0];
                // TODO detect osVersion
            }
        }
    }

    /**
     * Returns if device is Android mobile device
     * @return boolean
     */
    public function isAndroid(): bool
    {
        return $this->osName === 'Android';
    }

    /**
     * Returns if device is iOS mobile device
     * @return boolean
     */
    public function isIOS(): bool
    {
        return in_array($this->osName, ['iOS', 'iPhone', 'iPad', 'iPod']);
    }

    /**
     * Returns if device is Mobile
     * @return boolean
     */
    public function isMobile(): bool
    {
        return $this->isAndroid() || $this->isIOS() || in_array($this->osName, ['webOS', 'BlackBerry', 'Windows Phone']);
    }
}
