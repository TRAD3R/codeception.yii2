<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 13/12/2018
 * Time: 14:45
 */
namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use app\modules\user\models;
/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
  public $email;
  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      ['email', 'trim'],
      ['email', 'required'],
      ['email', 'email'],
      ['email', 'exist',
        'targetClass' => 'app\modules\user\models\User',
        'filter' => ['status' => User::STATUS_ACTIVE],
        'message' => Yii::t('app', 'NO_USER_EMAIL')
      ],
    ];
  }
  /**
   * Sends an email with a link, for resetting the password.
   *
   * @return bool whether the email was send
   */
  public function sendEmail()
  {
    /* @var $user User */
    $user = User::findOne([
      'status' => User::STATUS_ACTIVE,
      'email' => $this->email,
    ]);
    if (!$user) {
      return false;
    }

    if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
      $user->generatePasswordResetToken();
      if (!$user->save()) {
        return false;
      }
    }
    return Yii::$app
      ->mailer
      ->compose('@app/modules/user/mails/passwordReset', ['user' => $user])
      ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
      ->setTo($this->email)
      ->setSubject(Yii::t('app', 'PASSWORD_RESET_FOR') . Yii::$app->name)
      ->send();
  }
}