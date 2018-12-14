<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 13/12/2018
 * Time: 14:47
 */

namespace app\modules\user\models;

use app\modules\user\models;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;
/**
 * Password reset form
 */
class PasswordResetForm extends Model
{
  public $password;
  /**
   * @var \app\modules\user\models\User;
   */
  private $_user;
  /**
   * Creates a form model given a token.
   *
   * @param string $token
   * @param array $config name-value pairs that will be used to initialize the object properties
   * @throws InvalidArgumentException if token is empty or not valid
   */
  public function __construct($token, $config = [])
  {
    if (empty($token) || !is_string($token)) {
      throw new InvalidArgumentException(\Yii::t('app', 'EMPTY_TOKEN'));
    }
    $this->_user = User::findByPasswordResetToken($token);
    if (!$this->_user) {
      throw new InvalidArgumentException(\Yii::t('app', 'WRONG_TOKEN'));
    }
    parent::__construct($config);
  }

  public function attributeLabels()
  {
    return [
      'password' => Yii::t('app', 'USER_PASSWORD'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      ['password', 'required'],
      ['password', 'string', 'min' => 6],
    ];
  }
  /**
   * Resets password.
   *
   * @return bool if password was reset.
   */
  public function resetPassword()
  {
    $user = $this->_user;
    $user->setPassword($this->password);
    $user->removePasswordResetToken();
    return $user->save(false);
  }
}