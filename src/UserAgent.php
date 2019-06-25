<?php
/**
 * @Author: Martin Štěpánek
 * @Date: 31/03/2019 04:26
 */

namespace damidevelopment\apiutils;

use yii\base\BaseObject;
use yii\web\BadRequestHttpException;

class UserAgent extends BaseObject
{
    const OS_ANDROID = 0;
    const OS_IOS = 1;

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
     * @var int
     */
    private $_os;

    /**
     * @var string
     */
    public $osVersion;

    /**
     * @var string
     */
    public $deviceType;

    /**
     * @var string
     */
    public $uid;

    public function __construct(string $userAgent, array $config = [])
    {
        parent::__construct($config);
        $this->configure($userAgent);
    }

    public function setOs(string $os): self
    {
        if ($os === 'Android') {
            $this->_os = UserAgent::OS_ANDROID;
        }
        if ($os === 'iOS') {
            $this->_os = UserAgent::OS_IOS;
        }
        return $this;
    }

    public function getOs(): int
    {
        return $this->_os;
    }

    /** Configures UserAgent object by string provided in header
     *
     * {appName}/{appVersion}/{buildNumber}/{bundleId} {OS}/{OSversion} ({deviceType};{deviceId})
     *
     * Example:
     * huntigApp/1.0.3/28/com.damidev.huntingApp Android/9.0.0 (HTC_DesireS_S510e;4e4c5141b7cb261e)
     *
     * @param string $userAgent UserAgent information
     */
    private function configure(string $userAgent): void
    {
        try {
            $pattern = '#([a-zA-Z0-9 ]+)\/([a-zA-Z0-9. ]+)\/([a-zA-Z0-9 ]+)\/([a-zA-Z0-9 .]+) ([a-zA-Z0-9 ]+)\/([a-zA-Z0-9 .]+)\(([a-zA-Z0-9 _.]+);([a-zA-Z0-9 -]+)\)#';
            preg_match($pattern, $userAgent, $matches);

            $this->appName = $matches[1];
            $this->appVersion = $matches[2];
            $this->buildNumber = $matches[3];
            $this->bundleId = $matches[4];

            $this->setOs($matches[5]);
            $this->osVersion = $matches[6];

            $this->deviceType = $matches[7];
            $this->uid = $matches[8];
        } catch (\Exception $e){
            throw new BadRequestHttpException('Invalid UserAgent');
        }
    }

}