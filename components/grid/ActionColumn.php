<?php
/**
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 15/12/2018
 * Time: 15:05
 */

namespace app\components\grid;

class ActionColumn extends \yii\grid\ActionColumn
{
  public $contentOptions = [
    'class' => 'action-column',
  ];
}