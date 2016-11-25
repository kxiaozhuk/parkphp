<?php
/**
 * Created by PhpStorm.
 * User: zorochen
 * Date: 2016/11/19
 * Time: 下午11:47
 */

namespace app\dao;


use yii\db\ActiveRecord;

/**
 * @property String $usr_no
 */
class UserInfo extends ActiveRecord
{
    public static function tableName(){
        return 'fat_user_info';
    }
}