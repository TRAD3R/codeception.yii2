<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 13/12/2018
 * Time: 16:05
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = Yii::t('app', 'RESET_PASSWORD');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-password-reset">
  <h1><?= Html::encode($this->title) ?></h1>

  <p><?=Yii::t('app', 'CHOOSE_NEW_PASSWORD')?></p>

  <div class="row">
    <div class="col-lg-5">
      <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

      <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

      <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'BUTTON_SAVE'), ['class' => 'btn btn-primary']) ?>
                </div>

      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>