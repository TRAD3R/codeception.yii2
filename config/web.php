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
  'components' => [
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
      ],
      'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
      ],
      'db' => $db,
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
