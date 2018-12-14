<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 13/12/2018
 * Time: 14:50
 *
 * Форма подтверждения Email-адреса EmailConfirmForm по примеру формы сброса пароля. В ней мы будем искать пользователя по токену, вызывая User::findByEmailConfirmToken, активировать его и очищать поле email_confirm_token
*/
namespace app\modules\user\models;

use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

class EmailConfirmForm extends Model
{
  /**
   * @var User
   */
  private $_user;

  /**
   * Creates a form model given a token.
   *
   * @param  string $token
   * @param  array $config
   * @throws \yii\base\InvalidParamException if token is empty or not valid
   */
  public function __construct($token, $config = [])
  {
    if (empty($token) || !is_string($token)) {
      throw new InvalidParamException(Yii::t('app', 'MISSING_VERIFICATION_CODE'));
    }
    $this->_user = User::findByEmailConfirmToken($token);
    if (!$this->_user) {
      throw new InvalidParamException(Yii::t('app', 'WRONG_TOKEN'));
    }
    parent::__construct($config);
  }

  /**
   * Confirm email.
   *
   * @return boolean if email was confirmed.
   */
  public function confirmEmail()
  {
    $user = $this->_user;
    $user->status = User::STATUS_ACTIVE;
    $user->removeEmailConfirmToken();

    return $user->save();
  }
}