<?php

namespace damidevelopment\apiutils;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    /**
     * @var UserAgent
     */
    public $userAgent;

    /**
     * @var string
     */
    public $accessToken;

    /**
     * @var User
     */
    public static $user;

    /** Creates user identity based on user agent information
     * @param UserAgent $userAgent Provided user agent
     * @param string $accessCode Access code
     * @return User
     */
    public static function create(UserAgent $userAgent): User
    {
        if (static::$user !== null) {
            return static::$user;
        }
        static::$user = new User([
            'userAgent' => $userAgent,
        ]);
        return static::$user;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::$user;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::$user;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::$user;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->userAgent->uid;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return true;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return true;
    }
}
