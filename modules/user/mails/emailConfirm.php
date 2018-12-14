<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 13/12/2018
 * Time: 14:44
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\modules\user\models\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/email-confirm', 'token' => $user->email_confirm_token]);
?>

Здравствуйте, <?= Html::encode($user->username) ?>!

Для подтверждения адреса пройдите по ссылке:

<?= Html::a(Html::encode($confirmLink), $confirmLink) ?>

Если Вы не регистрировались на нашем сайте, то просто удалите это письмо.