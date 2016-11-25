<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'oauth2' => [
            'class' => 'filsh\yii2\oauth2server\Module',
            'options' => [
                'token_param_name' => 'access_token',
                'access_lifetime' => 3600 * 24,

                'require_exact_redirect_uri' => false
            ],
            'storageMap' => [
                'user_credentials' => 'app\base\Ouser'
            ],
            'grantTypes' => [
                'client_credentials' => [
                    'class' => 'OAuth2\GrantType\ClientCredentials',
                    'allow_public_clients' => false
                ],
                'user_credentials' => [
                    'class' => 'OAuth2\GrantType\UserCredentials'
                ],
                'refresh_token' => [
                    'class' => 'OAuth2\GrantType\RefreshToken',
                    'always_issue_new_refresh_token' => true
                ]
            ],
        ],
    ],

    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '5rfvSt88DbpIN-gs_K_eLJU1xYabqdQi',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            /* @var $event Event */
            'on beforeSend' => function ($event) {
                /* @var $response \yii\web\Response */
                $response = $event->sender;
                // refer: https://github.com/yiisoft/yii2/blob/master/docs/guide/rest-error-handling.md
                // @todo not cool, may be output a image
                $format = $response->format;    // json/raw
                $httpStatus = $response->getStatusCode();

                switch ($format) {
                    case 'json':
                        if (!in_array($httpStatus, [401, 403, 400])) {
                            $response->setStatusCode(200);

                        }
                        break;
                    case 'raw':
                        break;
                    default:
                        Yii::error('unexpected response format', 'b');
                }

                // @todo 49999 special deal will update to constant
                if ($httpStatus == 401 && isset($response->data['code']) && $response->data['code'] == 49999) {

                    $domain = \Yii::$app->params['cookieDomain'];

                    $expires = time() - 3600 * 2;
                    $cookies = \Yii::$app->getResponse()->getCookies();

                    $list = [
                        [
                            'name' => 'user_id',
                            'value' => '',
                        ],
                        [
                            'name' => 'access_token',
                            'value' => '',
                        ],
                    ];

                    foreach ($list as $item) {
                        $cookies->add(new yii\web\Cookie([
                            'name' => $item['name'],
                            'value' => $item['value'],
                            'expire' => $expires,
                            'domain' => $domain,
                            'path' => '/',
                            'httpOnly' => false,
                        ]));
                    }
                }
            },
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'user' => [
            'identityClass' => 'app\base\Ouser',
            //'enableAutoLogin' => true,
            'enableAutoLogin' => false,
            'loginUrl' => null,

        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'POST auth/login' => 'oauth2/default/token',
                '<controller:\w+>/<id:\w+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'booking',

                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
