<?php

namespace yozh\widget;

use yozh\base\Module as BaseModule;

class Module extends BaseModule
{

	const MODULE_ID = 'widget';
	
	public $controllerNamespace = 'yozh\\' . self::MODULE_ID . '\controllers';
	
}
