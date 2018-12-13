<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 09.12.2018
 * Time: 16:07
 */

namespace yozh\widget\widgets;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yozh\base\components\helpers\ArrayHelper;
use yozh\base\interfaces\models\ActiveRecordInterface;
use yozh\base\traits\NormalizeAttributesTrait;
use yii\base\InvalidConfigException;
use yozh\widget\traits\WidgetTrait;

class DetailView extends \yii\widgets\DetailView
{
	const TEMPLATE = '<tr {contentOptions}><th {labelOptions}>{label}</th><td {valueOptions}>{value}</td></tr>';
	
	use NormalizeAttributesTrait, WidgetTrait {
		init as public initTarit;
	}
	
	public $tag = 'table';
	
	public $options;
	
	public $contentOptions = [];
	
	public $labelOptions = [];
	
	public $valueOptions = [
		'class' => 'text',
	];
	
	public $template;
	
	public $attributes = [];
	
	public $encodeLabel = true;
	
	public function init()
	{
		$this->options = $this->options ?? [
				'class' => 'table table-striped table-bordered detail-view',
			];
		
		$this->template = $this->template ?? static::TEMPLATE;
		
		if( empty( $this->model ) && is_array( $this->attributes ) && count( $this->attributes ) ) {
			$this->model = $this->attributes;
		}
		
		$this->attributes = $this->_normalizeAttributes( $this->model, $this->attributes ?? [] );
		
		static::initTarit();
		
		/*
		$this->options['contentOptions'] = array_merge( $this->contentOptions, $this->options['contentOptions'] ?? [] );
		$this->options['labelOptions']   = array_merge( $this->labelOptions, $this->options['labelOptions'] ?? [] );
		$this->options['valueOptions']   = array_merge( $this->valueOptions, $this->options['valueOptions'] ?? [] );
		*/
		
		$this->options['tag'] = $this->options['tag'] ?? $this->tag;
		
	}
	
	public function run()
	{
		print $this->_renderAttributes( $this->model, $this->attributes );
	}
	
	public function _renderAttributes( $Model, $attributes = [], $options = null )
	{
		$options = $options ?? $this->options;
		
		$rows = [];
		
		$i = 0;
		
		foreach( $attributes ?? [] as $key => $attribute ) {
			
			$attributeName = $attribute['attribute'] ?? false;
			
			if( $attributeName && isset( $attribute['value'] )
				&& $Model instanceof ActiveRecordInterface
				&& $references = $Model->getAttributeReferences( $attributeName )
			) {
				
				foreach( $references as $refName => $Reference ) {
					
					$refItems = $Model->getAttributeReferenceItems( $attributeName, $Reference );
					
					if( isset( $refItems[ $attribute['value'] ] ) ) {
						$attribute['value'] = $refItems[ $attribute['value'] ];
					}
					
				}
				
			}
			
			$rows[ $i ] = $this->_renderAttribute( $Model, $attribute, $key, $i++, $options );
		}
		
		$tag = ArrayHelper::remove( $options, 'tag', 'table' );
		
		return Html::tag( $tag, implode( "\n", $rows ), $options );
	}
	
	protected function _renderAttribute( $Model, $attribute, $key, $index, $options = null )
	{
		$attribute['valueOptions'] = $attribute['valueOptions'] ?? $this->valueOptions ?? [];
		
		if( is_iterable( $attribute['value'] ) || ( empty($attribute['value']) && isset( $attribute['nested'] ) && is_iterable( $attribute['nested'] ) ) ) {
			
			if( $attributes = $this->_normalizeAttributes( $attribute['value'], $attribute['nested'] ?? [] ) ) {
				
				// prevent to print widget ID classes in nested nodes
				unset( $options['class']['namespace'] );
				unset( $options['class']['widget'] );
				Html::removeCssClass( $options, 'detail-view' );
				
				$attribute['value'] = $this->_renderAttributes( $attribute['value'], $attributes, $options );
				
				if( !isset( $attribute['format'] ) || $attribute['format'] == 'text' ) {
					
					$attribute['format'] = [ 'html', 'config' => function( $config ) {
						$config->getHTMLDefinition( true )
						       ->addElement( 'label', 'Inline', 'Inline', 'Common' )
						;
					} ];
					
				}
				
				Html::addCssClass( $attribute['valueOptions'], 'nested' );
				
			}
			else {
				$attribute['value'] = null;
			}
			
		}
		else{
			$attribute['value'] = $this->formatter->format( $attribute['value'], $attribute['format'] );
		}
		
		if( is_string( $this->template ) ) {
			
			$attribute['contentOptions'] = Html::renderTagAttributes( $attribute['contentOptions'] ?? $this->contentOptions ?? [] );
			$attribute['labelOptions']   = Html::renderTagAttributes( $attribute['labelOptions'] ?? $this->labelOptions ?? [] );
			$attribute['valueOptions']   = Html::renderTagAttributes( $attribute['valueOptions'] ?? $this->valueOptions ?? [] );
			
			$replace = [];
			
			if( preg_match_all( '/{(.*?)}/', $this->template, $from ) ) {
				
				array_shift( $from );
				
				foreach( $from[0] as $key ) {
					$replace[ '{' . $key . '}' ] = $attribute[ $key ] ?? '';
				}
			}
			
			$output = strtr( $this->template, $replace );
		}
		else if( $this->template instanceof \Closure ) {
			$output = call_user_func( $this->template, $Model, $attribute, $key, $index, $this );
		}
		
		return $output;
		
	}
	
}