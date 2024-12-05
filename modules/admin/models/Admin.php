<?php
namespace app\modules\admin\models;


use yii\base\BaseObject;
use yii\web\IdentityInterface;

class Admin extends BaseObject implements IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    private static $users;
    private static function initializeUsers()
    {
        self::$users = [
            '1001' => [
                'id' => '1001',
                'username' => $_ENV['ADMIN_USERNAME'],
                'password' => $_ENV['ADMIN_PASSWORD'],
                'authKey' => $_ENV['ADMIN_AUTH_KEY'],
                'accessToken' => $_ENV['ADMIN_ACCESS_TOKEN'],
            ],
        ];
    }

    public static function findIdentity($id)
    {
        if (self::$users === null) {
            self::initializeUsers();
        }
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        if (self::$users === null) {
            self::initializeUsers();
        }
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    public static function findByUsername($username)
    {
        if (self::$users === null) {
            self::initializeUsers();
        }
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public static function setSessionAdmin($value){
        \Yii::$app->session->set('admin', $value);
    }
}
