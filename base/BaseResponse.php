<?php
/**
 * Created by PhpStorm.
 * User: zorochen
 * Date: 2016/11/20
 * Time: 上午1:17
 */

namespace app\base;


use yii\web\Response;

class BaseResponse extends Response
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_HTML;
        return $behaviors;
    }

}