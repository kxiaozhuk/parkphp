<?php
/**
 * Created by PhpStorm.
 * User: zorochen
 * Date: 2016/11/22
 * Time: 下午8:12
 */

namespace app\base;


use app\models\User;
use filsh\yii2\oauth2server\Module;
use OAuth2\Storage\UserCredentialsInterface;
use yii\db\ActiveRecord;

class Ouser extends User implements  UserCredentialsInterface
{

    public static function findIdentityByAccessToken($token, $type = null)
    {
        /** @var Module $module */
        $module = \Yii::$app->getModule('oauth2');
        $module->getServer()->getResourceController()->verifyResourceRequest($module->getRequest(),$module->getResponse());
        $token = $module->getServer()->getResourceController()->getToken();
        return !empty($token['user_id'])
            ? static::findIdentity($token['user_id'])
            : null;
    }

    /**
     * Grant access tokens for basic user credentials.
     *
     * Check the supplied username and password for validity.
     *
     * You can also use the $client_id param to do any checks required based
     * on a client, if you need that.
     *
     * Required for OAuth2::GRANT_TYPE_USER_CREDENTIALS.
     *
     * @param $username
     * Username to be check with.
     * @param $password
     * Password to be check with.
     *
     * @return
     * TRUE if the username and password are valid, and FALSE if it isn't.
     * Moreover, if the username and password are valid, and you want to
     *
     * @see http://tools.ietf.org/html/rfc6749#section-4.3
     *
     * @ingroup oauth2_section_4
     */
    public function checkUserCredentials($username, $password)
    {
        $user = static::findByUsername($username);
        if (empty($user)) {
            return false;
        }
        return $user->validatePassword($password);
    }

    /**
     * @return
     * ARRAY the associated "user_id" and optional "scope" values
     * This function MUST return FALSE if the requested user does not exist or is
     * invalid. "scope" is a space-separated list of restricted scopes.
     * @code
     * return array(
     *     "user_id"  => USER_ID,    // REQUIRED user_id to be stored with the authorization code or access token
     *     "scope"    => SCOPE       // OPTIONAL space-separated list of restricted scopes
     * );
     * @endcode
     */
    public function getUserDetails($username)
    {
        $user = static::findByUsername($username);
        return ['user_id' => $user->getId()];
        // TODO: Implement getUserDetails() method.
    }
}