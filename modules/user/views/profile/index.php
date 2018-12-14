<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 14/12/2018
 * Time: 15:16
 *
 * будем просто выводить информацию о пользователе с помощью виджета DetailView
 */

use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = Yii::t('app', 'TITLE_PROFILE');
$this->params['breadcrumbs'][] = $this->title;

echo Nav::widget([
  'options' => ['class' => 'navbar-nav navbar-right'],
  'items' => array_filter([
    ['label' => Yii::t('app', 'NAV_HOME'), 'url' => ['/main/default/index']],
    Yii::$app->user->isGuest ?
      ['label' => Yii::t('app', 'NAV_SIGNUP'), 'url' => ['/user/default/signup']] :
      false,
    Yii::$app->user->isGuest ?
      ['label' => Yii::t('app', 'NAV_LOGIN'), 'url' => ['/user/default/login']] :
      false,
    !Yii::$app->user->isGuest ?
      ['label' => Yii::t('app', 'NAV_PROFILE'), 'url' => ['/user/profile/index']] :
      false,
    !Yii::$app->user->isGuest ?
      ['label' => Yii::t('app', 'NAV_PROFILE_UPDATE'), 'url' => ['/user/profile/update']] :
      false,
    !Yii::$app->user->isGuest ?
      ['label' => Yii::t('app', 'NAV_PASSWORD_CHANGE'), 'url' => ['/user/profile/password-change']] :
      false,
    ['label' => Yii::t('app', 'NAV_CONTACT'), 'url' => ['/main/contact/index']],
    !Yii::$app->user->isGuest ?
      ['label' => Yii::t('app', 'NAV_LOGOUT'),
        'url' => ['/user/default/logout'],
        'linkOptions' => ['data-method' => 'post']] :
      false,
  ]),
]);
?>
<div class="user-profile">

  <h1><?= Html::encode($this->title) ?></h1>

  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      'username',
      'email',
    ],
  ]) ?>

</div>
