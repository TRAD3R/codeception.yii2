<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 14/12/2018
 * Time: 15:35
 */

namespace app\modules\user\models;

use yii\base\Model;
use yii\db\ActiveQuery;
use Yii;

class ProfileUpdateForm extends Model
{
  public $email;
  public $username;

  /**
   * @var User
   */
  private $_user;

  public function __construct(User $user, $config = [])
  {
    $this->_user = $user;
    $this->email = $user->email;
    $this->username = $user->username;
    parent::__construct($config);
  }

  public function rules()
  {
    return [
      ['username', 'required'],
      ['username', 'match', 'pattern' => '#^[\w_-]+$#is'],
      [
        'username',
        'unique',
        'targetClass' => self::className(),
        'message' => Yii::t('app', 'USERNAME_ISSET')
      ],
      ['username', 'string', 'min' => 2, 'max' => 255],

      ['email', 'required'],
      ['email', 'email'],
      [
        'email',
        'unique',
        'targetClass' => User::className(),
        'message' => Yii::t('app', 'ERROR_EMAIL_EXISTS'),
        'filter' => ['<>', 'id', $this->_user->id],
      ],
      ['email', 'string', 'max' => 255],
    ];
  }

  public function update()
  {
    if ($this->validate()) {
      $user = $this->_user;
      $user->email = $this->email;
      $user->username = $this->username;
      return $user->save();
    } else {
      return false;
    }
  }
}