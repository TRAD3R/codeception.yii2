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
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
              [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error'],
                'logFile' => '@app/runtime/logs/console-error.log'
              ],
              [
                'class' => 'yii\log\FileTarget',
                'levels' => ['warning'],
                'logFile' => '@app/runtime/logs/console-warning.log'
              ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
