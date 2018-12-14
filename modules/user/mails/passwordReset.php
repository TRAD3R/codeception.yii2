<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 13/12/2018
 * Time: 14:54
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\modules\user\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/password-reset', 'token' => $user->password_reset_token]);
?>

  Здравствуйте, <?= Html::encode($user->username) ?>!

  Пройдите по ссылке, чтобы сменить пароль:

<?= Html::a(Html::encode($resetLink), $resetLink) ?>