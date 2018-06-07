<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 10.05.2018
 * Time: 9:58
 */

namespace yozh\widget\widgets;

use yii\bootstrap\BootstrapWidgetTrait;
use yozh\widget\widgets\BaseWidget as Widget;

class BaseBootstrapWidget extends Widget
{
	use BootstrapWidgetTrait;
	
	/**
	 * @var array the HTML attributes for the widget container tag.
	 * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
	 */
	public $options = [];
}