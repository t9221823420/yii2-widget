<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 10.05.2018
 * Time: 9:44
 */

namespace yozh\widget\widgets;

use Yii;
use yii\base\Model;
use yozh\widget\traits\WidgetTrait;
use yozh\widget\widgets\BaseBootstrapWidget as Widget;
use yii\helpers\Html;
use yozh\base\components\utils\Config;
use yozh\widget\traits\ActiveInputTarit;

class ActiveButton extends Widget
{
	use ActiveInputTarit;
	
	const TYPE_DEFAULT = 'default';
	const TYPE_YES     = 'yes';
	const TYPE_NO      = 'no';
	
	const TYPES_CLASS = [
		self::TYPE_DEFAULT => 'default',
		self::TYPE_YES     => 'success',
		self::TYPE_NO      => 'danger',
	];
	
	/**
	 * @var string the tag to use to render the button
	 */
	public $tagName = 'button';
	/**
	 * @var string the button label
	 */
	public $label;
	
	/**
	 * @var bool whether the label should be HTML-encoded.
	 */
	public $encodeLabel = true;
	
	/**
	 * @var string url for server ation.
	 */
	public $url;
	
	/**
	 * @var string url for server ation.
	 */
	public $action;
	
	/**
	 * @var string call
	 */
	public $done;
	
	/**
	 * @var bool whether the label should be HTML-encoded.
	 */
	public $fail;
	
	/**
	 * @var bool whether the label should be HTML-encoded.
	 */
	public $confirm = false;
	
	/**
	 * @var bool whether the label should be HTML-encoded.
	 */
	public $data = [];
	
	
	/**
	 * @var Model the data model that this widget is associated with..
	 */
	public $model;
	
	/**
	 * @var string the model attribute that this widget is associated with.
	 */
	public $attributes;
	
	/**
	 * @var string the model attribute that this widget is associated with.
	 */
	public $type;
	
	public $options;
	
	/**
	 * Renders the widget.
	 */
	public function run()
	{
		//$this->registerPlugin('button');
		
		$params = [];
		
		$View = Yii::$app->controller->view;
		
		/*
		$options = ArrayHelper::merge( [
			'class' => '',
		], $this->options );
		*/
		
		$options = $this->options;
		
		$processProperties = [
			'url',
			'action',
			'confirm',
			'done',
			'fail',
			'method',
		];
		
		foreach( $processProperties as $property ) {
			if( $this->$property ?? false ) {
				
				$options[ $property ] = Config::setWithClosure( $this->$property );
				
				// if ommit - href will be empty
				if ( $property == 'url' && $this->tagName == 'a' && !empty($this->url) ){
					$options['href'] = $this->url;
					//unset($options['url']); comment because of not processed as ActiveButton after action
				}
			}
		}
		
		$this->data = Config::setWithClosure( $this->data );
		
		if( !is_array( $this->data ) ) { //
			throw new \yii\base\InvalidParamException( "Param 'data' have to be an array or Closure which have to be return an array." );
		}
		
		foreach( $this->data as $key => $datum ) {
			
			// [ 'attribute1', 'attribute1',  ]
			if( is_numeric( $key ) && is_string( $datum ) ) {
				$options[ 'data-param' . $key ] = $datum;
			}
			// [ 'attr' => 'some value', 'value' => 'another value'  ]
			else if( is_string( $key ) ) {
				$options[ 'data-' . $key ] = $datum;
			}
		}
		
		if( $this->model instanceof Model ) {
			
			$attributes = null;
			
			if( $this->attributes ?? false ) {
				$attributes = $this->attributes;
			}
			
			$attributes = $this->model->getAttributes( $attributes );
			
			foreach( $attributes as $name => $value ) {
				
				$options[ 'data-' . $name ] = $value;
				
			}
			
		}
		
		if( in_array( $this->type, self::getConstants( 'TYPE_' ) ) ) {
			
			Html::addCssClass( $options, [ 'type' => 'yozh-widget-active-button-' . $this->type ] );
			Html::addCssClass( $options, [ 'bootstrap' => 'btn btn-' . static::TYPES_CLASS[ $this->type ] ] );
			
			if( !$this->label ) {
				$this->label = Yii::t( 'app', ucfirst( $this->type ) );
			}
			
		}
		
		$this->options = $options;
		
		\yozh\widget\AssetBundle::register( $View );
		
		return Html::tag( $this->tagName, $this->encodeLabel
			? Html::encode( $this->label ?? Yii::t( 'app', 'Button' ) )
			: $this->label, $this->options
		);
		
	}
	
}