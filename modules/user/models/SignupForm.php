<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 13/12/2018
 * Time: 14:41
 */

namespace app\modules\user\models;

use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
  public $username;
  public $email;
  public $password;
  public $verifyCode;

  public function attributeLabels()
  {
    return [
      'username' => Yii::t('app', 'USER_USERNAME'),
      'email' => Yii::t('app', 'USER_EMAIL'),
      'password' => Yii::t('app', 'USER_PASSWORD'),
      'verifyCode' => Yii::t('app', 'VERIFY_CODE'),
    ];
  }

  public function rules()
  {
    return [
      ['username', 'filter', 'filter' => 'trim'],
      ['username', 'required'],
      ['username', 'match', 'pattern' => '#^[\w_-]+$#i'],
      ['username', 'unique', 'targetClass' => User::className(), 'message' => Yii::t('app', 'USERNAME_ISSET')],
      ['username', 'string', 'min' => 2, 'max' => 255],

      ['email', 'filter', 'filter' => 'trim'],
      ['email', 'required'],
      ['email', 'email'],
      ['email', 'unique', 'targetClass' => User::className(), 'message' => Yii::t('app', 'EMAIL_ISSET')],

      ['password', 'required'],
      ['password', 'string', 'min' => 6],

      ['verifyCode', 'captcha', 'captchaAction' => '/user/default/captcha'],
    ];
  }

  /**
   * Signs user up.
   *
   * @return User|null the saved model or null if saving fails
   */
  public function signup()
  {
    if ($this->validate()) {
      $user = new User();
      $user->username = $this->username;
      $user->email = $this->email;
      $user->setPassword($this->password);
      $user->status = User::STATUS_WAIT;
      $user->generateAuthKey();
      $user->generateEmailConfirmToken();

      if ($user->save()) {
        Yii::$app->mailer->compose('@app/modules/user/mails/emailConfirm', ['user' => $user])
          ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
          ->setTo($this->email)
          ->setSubject(Yii::t('app', 'EMAIL_CONFIRMATION_FOR') . Yii::$app->name)
          ->send();
        return $user;
      }
    }

    return null;
  }
}