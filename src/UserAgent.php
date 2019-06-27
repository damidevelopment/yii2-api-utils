<?php
/**
 * @Author: Martin Štěpánek
 * @Date: 31/03/2019 04:26
 */

namespace damidevelopment\apiutils;

use yii\base\BaseObject;
use yii\web\BadRequestHttpException;

/**
 * UserAgent
 *
 * @property string $appName
 * @property string $appVersion
 * @property string $buildNumber
 * @property string $bundleId
 * @property int $os
 * @property string $osVersion
 * @property string $deviceType
 * @property string $uid
 */
class UserAgent extends BaseObject
{
    const OS_ANDROID = 0;
    const OS_IOS = 1;

    public $userAgentHeader = 'User-Agent';

    /**
     * @var string
     */
    private $_appName;

    /**
     * @var string
     */
    private $_appVersion;

    /**
     * @var string
     */
    private $_buildNumber;

    /**
     * @var string
     */
    private $_bundleId;

    /**
     * @var int
     */
    private $_os;

    /**
     * @var string
     */
    private $_osVersion;

    /**
     * @var string
     */
    private $_deviceType;

    /**
     * @var string
     */
    private $_uid;


    public function init()
    {
        $this->configure(\Yii::$app->request->headers->get($this->userAgentHeader));
    }

    /**
     * Configures UserAgent object by string provided in header
     *
     * {appName}/{appVersion}/{buildNumber}/{bundleId} {OS}/{OSversion} ({deviceType};{deviceId})
     *
     * Example:
     * huntigApp/1.0.3/28/com.damidev.huntingApp Android/9.0.0 (HTC_DesireS_S510e;4e4c5141b7cb261e)
     *
     * @param string $userAgent UserAgent information
     * @throws BadRequestHttpException
     */
    private function configure(string $userAgent): void
    {
        try {
            $pattern = '#([a-zA-Z0-9 ]+)\/([a-zA-Z0-9. ]+)\/([a-zA-Z0-9 ]+)\/([a-zA-Z0-9 .]+) ([a-zA-Z0-9 ]+)\/([a-zA-Z0-9 .]+)\(([a-zA-Z0-9 _.]+);([a-zA-Z0-9 -]+)\)#';
            preg_match($pattern, $userAgent, $matches);

            $this->_appName = $matches[1];
            $this->_appVersion = $matches[2];
            $this->_buildNumber = $matches[3];
            $this->_bundleId = $matches[4];

            $this->setOs($matches[5]);
            $this->_osVersion = $matches[6];

            $this->_deviceType = $matches[7];
            $this->_uid = $matches[8];
        } catch (\Exception $e) {
            throw new BadRequestHttpException(\Yii::t('errors', 'Invalid UserAgent'));
        }
    }

    /**
     * @return string
     */
    public function getAppName(): string
    {
        return $this->_appName;
    }

    /**
     * @return string
     */
    public function getAppVersion(): string
    {
        return $this->_appVersion;
    }

    /**
     * @return string
     */
    public function getBuildNumber(): string
    {
        return $this->_buildNumber;
    }

    /**
     * @return string
     */
    public function getBundleId(): string
    {
        return $this->_bundleId;
    }

    /**
     * @param string $os
     * @return UserAgent
     */
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

    /**
     * @return int
     */
    public function getOs(): int
    {
        return $this->_os;
    }

    /**
     * @return string
     */
    public function getOsVersion(): string
    {
        return $this->_osVersion;
    }

    /**
     * @return string
     */
    public function getDeviceType(): string
    {
        return $this->_deviceType;
    }

    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->_uid;
    }
}