<?php

namespace app\modules\user\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $username
 * @property string $auth_key
 * @property string $email_confirm_token
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 */
class User extends ActiveRecord  implements IdentityInterface
{
  /** #1 Первым делом, введём в неё константы для указания статуса, статический метод getStatusesArray для получения их списка и метод getStatusName для получения имени статуса пользователя. Эти методы пригодятся, например, при выводе пользователей в панели управления  */
  const STATUS_BLOCKED = 0;
  const STATUS_ACTIVE = 1;
  const STATUS_WAIT = 2;
  /** #1 */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /** #2 Теперь изменим сгенерированные автоматически правила валидации. Просто оставим только те поля, которые могут понадобиться при редактировании пользователя. Также переведём имена полей в методе attributeLabels и введём проверку на правильность статуса */
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
      return [
        ['username', 'required'],
        ['username', 'match', 'pattern' => '#^[\w_-]+$#is'],
        ['username', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('app', 'USERNAME_ISSET')],
        ['username', 'string', 'min' => 2, 'max' => 255],

        ['email', 'required'],
        ['email', 'email'],
        ['email', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('app', 'EMAIL_ISSET')],
        ['email', 'string', 'max' => 255],

        ['status', 'integer'],
        ['status', 'default', 'value' => self::STATUS_ACTIVE],
        ['status', 'in', 'range' => array_keys(self::getStatusesArray())],
      ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
      return [
        'id' => 'ID',
        'created_at' => 'Создан',
        'updated_at' => 'Обновлён',
        'username' => 'Имя пользователя',
        'email' => 'Email',
        'status' => 'Статус',
      ];
    }
  /** #2 */

  /** #3 Также не забудем, что у нас имеются поля created_at и updated_at, в которые нужно вписывать дату при создании и каждом обновлении записи. Для этого нам пригодится уже имеющееся в Yii2 поведение */
  public function behaviors()
  {
    return [
      TimestampBehavior::className(),
    ];
  }
  /** #3 */

  /** ... #1 */
  public function getStatusName()
  {
    return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
  }

  public static function getStatusesArray()
  {
    return [
      self::STATUS_BLOCKED => Yii::t('app', 'STATUS_BLOCKED'),
      self::STATUS_ACTIVE => Yii::t('app', 'STATUS_ACTIVE'),
      self::STATUS_WAIT => Yii::t('app', 'STATUS_WAIT'),
    ];
  }
  /** #1 */

  /** #4 В старой модели наш класс заодно осуществлял хранение авторизованного пользователя в Yii::$app->user->identity и для этого реализовывал интерфейс IdentityInterface. Допишем методы, которые требует добавить этот интерфейс. Коды методов позаимствуем из того же расширенного шаблона приложения */
  public static function findIdentity($id)
  {
    return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
  }

  public static function findIdentityByAccessToken($token, $type = null)
  {
    throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
  }

  public function getId()
  {
    return $this->getPrimaryKey();
  }

  public function getAuthKey()
  {
    return $this->auth_key;
  }

  public function validateAuthKey($authKey)
  {
    return $this->getAuthKey() === $authKey;
  }
  /** #4 */

  /** #5 В старой модели также были findByUsername и validatePassword для работы класса LoginForm. Добавим и их. В методе findByUsername не будем искать по статусу User::STATUS_ACTIVE. Так как у нас много статусов, то проверки удобнее будет совершать в контроллере или в форме LoginForm. Для хэширования паролей будем использовать новый компонент Security */
  /**
   * Finds user by username
   *
   * @param string $username
   * @return static|null
   */
  public static function findByUsername($username)
  {
    return static::findOne(['username' => $username]);
  }

  /**
   * Validates password
   *
   * @param string $password password to validate
   * @return boolean if password provided is valid for current user
   */
  public function validatePassword($password)
  {
    return Yii::$app->security->validatePassword($password, $this->password_hash);
  }
  /** #5 */

  /** #6 Перед записью в базу для каждого пользователя нужно генерировать хэш пароля и дополнительный ключ автоматической аутентификации. Добавим методы их генерации и сделаем второй метод автозапускаемым при создании записи */
  /**
   * @param string $password
   */
  public function setPassword($password)
  {
    $this->password_hash = Yii::$app->security->generatePasswordHash($password);
  }

  /**
   * Generates "remember me" authentication key
   */
  public function generateAuthKey()
  {
    $this->auth_key = Yii::$app->security->generateRandomString();
  }

  public function beforeSave($insert)
  {
    if (parent::beforeSave($insert)) {
      if ($insert) {
        $this->generateAuthKey();
      }
      return true;
    }
    return false;
  }
  /** #6 */

  /** #7 Теперь добавим возможность смены пароля. Для этого у нас предусмотрено поле password_reset_token. При запросе восстановления мы в это поле будем записывать уникальную случайную строку с временной меткой и посылать по электронной почте ссылку с этим хешэм на контроллер с действием активаци. А в контроллере уже найдём этого пользователя по данному хешу и поменяем ему пароль. Добавим методы для генерации хеша и поиска по нему */
  /**
   * Finds user by password reset token
   *
   * @param string $token password reset token
   * @return static|null
   */
  public static function findByPasswordResetToken($token)
  {
    if (!static::isPasswordResetTokenValid($token)) {
      return null;
    }
    return static::findOne([
      'password_reset_token' => $token,
      'status' => self::STATUS_ACTIVE,
    ]);
  }

  /**
   * Finds out if password reset token is valid
   *
   * @param string $token password reset token
   * @return boolean
   */
  public static function isPasswordResetTokenValid($token)
  {
    if (empty($token)) {
      return false;
    }
    $expire = Yii::$app->params['user.passwordResetTokenExpire'];
    $parts = explode('_', $token);
    $timestamp = (int) end($parts);
    return $timestamp + $expire >= time();
  }

  /**
   * Generates new password reset token
   */
  public function generatePasswordResetToken()
  {
    $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
  }

  /**
   * Removes password reset token
   */
  public function removePasswordResetToken()
  {
    $this->password_reset_token = null;
  }
  /** #7 */

  /** #8 Для регистрирующихся пользователей не помешает сделать подтверждение адреса электронной почты. Для этой цели добавим несколько методов для управления email_confirm_token. При регистрации мы будем присваивать пользователю статус STATUS_WAIT, генерировать ключ и отправлять ссылку с ключом на почту. А в контроллере (при переходе по этой ссылке) найдём пользователя по ключу и активируем */
  /**
   * @param string $email_confirm_token
   * @return static|null
   */
  public static function findByEmailConfirmToken($email_confirm_token)
  {
    return static::findOne(['email_confirm_token' => $email_confirm_token, 'status' => self::STATUS_WAIT]);
  }

  /**
   * #При регистрации мы будем присваивать пользователю статус STATUS_WAIT, генерировать ключ и отправлять ссылку с ключом на почту. А в контроллере (при переходе по этой ссылке) найдём пользователя по ключу и активируем
   */
  /**
   * Generates email confirmation token
   */
  public function generateEmailConfirmToken()
  {
    $this->email_confirm_token = Yii::$app->security->generateRandomString();
  }

  /**
   * Removes email confirmation token
   */
  public function removeEmailConfirmToken()
  {
    $this->email_confirm_token = null;
  }
  /** #8 */

}
