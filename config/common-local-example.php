<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 14/12/2018
 * Time: 14:11
 * Fill data and remove "-example" from title
 *
 * For gmail you need to create new password for app
 * enable the 2-step verification to google https://www.google.com/landing/2step/
 * Create App Password to be use by your system https://security.google.com/settings/security/apppasswords
 */

return [
  'components' => [
    'mailer' => [
      // send all mails to a file by default. You have to set
      // 'useFileTransport' to false and configure a transport
      // for the mailer to send real emails.
      'useFileTransport' => false,
      'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.gmail.com',
        'username' => '<username>@<domain>',
        'password' => '<password>',
        'port' => 465,
        'encryption' => 'ssl',
      ],
    ],
    'cache' => [
      'class' => 'yii\caching\FileCache',
    ],
  ],
];