<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 14/12/2018
 * Time: 15:13
 */
namespace app\modules\user\controllers;

use app\modules\user\models\ProfileUpdateForm;
use app\modules\user\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;

class ProfileController extends Controller
{
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'rules' => [
          [
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
    ];
  }

  public function actionIndex()
  {
    return $this->render('index', [
      'model' => $this->findModel(),
    ]);
  }

  /**  #1 при авторизации в качестве Yii::$app->user->identity используется именно экземпляр модели User текущего пользователя. Это значит, что в качестве $model мы могли бы использовать прямо этот экземпляр Yii::$app->user->identity. Но безопаснее в ходе выполнения действия контроллера не давать доступа к самому экземпляру Yii::$app->user->identity. Поэтому мы ещё раз загрузили экземпляр пользователя из базы данных */
  /**
   * @return User the loaded model
   */
  private function findModel()
  {
    return User::findOne(Yii::$app->user->identity->getId());
  }
  /** #1 */

  public function actionUpdate()
  {
    $user = $this->findModel();
    $model = new ProfileUpdateForm($user);

    if ($model->load(Yii::$app->request->post()) && $model->update()) {
      return $this->redirect(['index']);
    } else {
      return $this->render('update', [
        'model' => $model,
      ]);
    }
  }
}