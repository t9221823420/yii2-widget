<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 10.05.2018
 * Time: 9:43
 */

namespace yozh\widget;

use yii\base\Widget;
use yii\helpers\Html;
use yozh\base\traits\ObjectTrait;
use yozh\widget\traits\BaseWidgetTrait;

class BaseWidget extends Widget
{
	use BaseWidgetTrait, ObjectTrait;
}