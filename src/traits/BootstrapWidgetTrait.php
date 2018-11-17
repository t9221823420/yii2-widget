<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 22.09.2018
 * Time: 11:23
 */

namespace yozh\widget\traits;

trait BootstrapWidgetTrait
{
	use \yii\bootstrap\BootstrapWidgetTrait, WidgetTrait{
		WidgetTrait::init as public baseInitTrait;
	}
	
	public function init()
	{
		$this->baseInitTrait();
		
		parent::init();
		
		if (!isset($this->options['id'])) {
			$this->options['id'] = $this->getId();
		}
	}
	
}