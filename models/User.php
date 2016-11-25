<?php

namespace app\models;

use app\dao\UserInfo;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{

    public static function tableName(){
        return 'oauth_users';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {

        return self::findOne(['user_id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $u = self::find()->one();
        var_dump($u);die();
        return self::find()->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return  static::findOne(['username' => $username]);
        //return $userInfo = UserInfo::findOne(['user_name' => $username]);
//        if(!empty($userInfo)){
//            return User::findOne(['user_no' => $userInfo->usr_no]);
//        }
//        return null;
        /*
        if(!empty($userInfo)){
            $user_no = $userInfo->usr_no;
        }

        return static::findOne(['usr_nm' => $username]);
        */
        /*
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
        */
    }

    /**
     * @inheritdoc
     *
     */
    public function getId()
    {
        return $this->getPrimaryKey();

    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        var_dump('au');die();
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        var_dump("valid");die();
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        //\Yii::$app->security->passwordHashCost($password)
        //return $this->password === $password;
        return $this->password == $password;
    }

    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

}
