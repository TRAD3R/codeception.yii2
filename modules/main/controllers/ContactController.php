<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 13/12/2018
 * Time: 12:42
 */

namespace app\modules\main\controllers;


use app\modules\main\models\ContactForm;
use Yii;
use yii\web\Controller;

class ContactController extends Controller
{
  public function actions()
  {
    return [
      'captcha' => [
        'class' => 'yii\captcha\CaptchaAction',
        'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
      ],
    ];
  }

  public function actionIndex()
  {
    $model = new ContactForm();
    if ($user = Yii::$app->user->identity) {
      /** @var \app\modules\user\models\User $user */
      $model->name = $user->username;
      $model->email = $user->email;
    }
    if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
      Yii::$app->session->setFlash('contactFormSubmitted');
      return $this->refresh();
    } else {
      return $this->render('index', [
        'model' => $model,
      ]);
    }
  }
}