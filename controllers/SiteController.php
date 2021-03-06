<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\web\Response;

class SiteController extends Controller
{
    public $modelClass = '';
    public $enableCsrfCookie = false;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'login' => ['post'],
                ],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'except' => [],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ]
        ];
    }
    public function actions()
    {
        return [];
    }


    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return [
                'is_login' => true
            ];
            //return $this->goHome();
        }

        $model = new LoginForm();
        $model->attributes = Yii::$app->request->post();


        if ($model->validate() && $model->login()) {

            //$this->   Yii::$app->getRequest()->getUserIP();
            //$this->access_token =

            return [
                'is_login' => true
            ];
        }
        return [
            'is_login'  => false,

        ];
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return ['logout' => true];
    }
    public function actionError(){
        return ['error' => 1];
    }
    public function afterAction($action, $result) {

        $result = [
            'code' 		=> 0,
            'message' 	=> 'success',
            'data' 		=> $result,
        ];

        return $result;
    }

}
