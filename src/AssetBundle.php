<?php

namespace yozh\widget;

class AssetBundle extends \yozh\base\AssetBundle
{

    public $sourcePath = __DIR__ .'/../assets/';

    public $css = [
        //'css/yozh-widget.css',
	    //['css/yozh-widget.print.css', 'media' => 'print'],
    ];
	
    public $js = [
        'js/yozh-widget.js',
        'js/yozh-modal.js',
    ];
	
	public $depends = [
		\yozh\base\AssetBundle::class,
	];
	
	public $publishOptions = [
		'forceCopy'       => true,
	];
	
}