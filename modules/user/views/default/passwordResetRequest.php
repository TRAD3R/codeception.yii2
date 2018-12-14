<?php
$this->title = Yii::t('app', 'TITLE_RESET_PASSWORD');
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>
<div class="user-default-password-reset-request">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'FILL_FOR_RESET_REQUEST') ?></p>

    <div class="row">
        <div class="col-lg-5">
          <?php $form = ActiveForm::begin(['id' => 'password-reset-request-form']); ?>
          <?= $form->field($model, 'email') ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'BUTTON_SEND'), ['class' => 'btn btn-primary']) ?>
            </div>
          <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>