<?php

use yii\helpers\ArrayHelper;

$params = ArrayHelper::merge(
  require(__DIR__ . '/params.php'),
  require(__DIR__ . '/params-local.php')
);
$db = ArrayHelper::merge(
  require(__DIR__ . '/db.php'),
  require(__DIR__ . '/db-local.php')
);

$config = [
  'id' => 'basic',
  'language' => 'ru-RU',
  'defaultRoute' => 'main/default/index',
  'basePath' => dirname(__DIR__),
  'bootstrap' => ['log'],
  'aliases' => [
      '@bower' => '@vendor/bower-asset',
      '@npm'   => '@vendor/npm-asset',
  ],
  'modules' => [
    'main' => [
      'class' => 'app\modules\main\Module',
    ],
    'user' => [
      'class' => 'app\modules\user\Module',
    ],
  ],
  'components' => [
    'request' => [
        // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
        'cookieValidationKey' => 'dBUpEy0ze7kYnPqhZOaedppvtHqgn4pr',
    ],
    'assetManager' => [
      'linkAssets' => false,
    ],
      'cache' => [
          'class' => 'yii\caching\FileCache',
      ],
      'user' => [
        'identityClass' => 'app\modules\user\models\User',
        'enableAutoLogin' => true,
        'loginUrl' => ['user/default/login'],
      ],
      'errorHandler' => [
        'errorAction' => 'main/default/error',
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
            'levels' => ['error'],
            'logFile' => '@app/runtime/logs/web-error.log'
          ],
          [
            'class' => 'yii\log\FileTarget',
            'levels' => ['warning'],
            'logFile' => '@app/runtime/logs/web-warning.log'
          ],
        ],
      ],
      'db' => $db,
//    'urlManager' => [
//      'enablePrettyUrl' => true,
//      'showScriptName' => false,
//      'rules' => [
//        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
//        '<_c:[\w\-]+>' => '<_c>/index',
//        '<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_c>/<_a>',
//      ],
//    ],
  ],
  'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
