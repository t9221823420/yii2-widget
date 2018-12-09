<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 09.12.2018
 * Time: 16:07
 */

namespace yozh\widget\widgets;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\Html;
use yozh\base\components\helpers\ArrayHelper;
use yozh\base\components\helpers\Inflector;

class DetailView extends \yii\widgets\DetailView
{
	public function renderAttributes( $attributes = [] )
	{
		$rows = [];
		
		$i = 0;
		
		foreach( $attributes as $attribute ) {
			$rows[] = $this->renderAttribute( $attribute, $i++ );
		}
		
		$options = $this->options;
		$tag     = ArrayHelper::remove( $options, 'tag', 'table' );
		
		return Html::tag( $tag, implode( "\n", $rows ), $options );
	}
	
	public function run()
	{
		print $this->renderAttributes( $this->attributes );
	}
	
	protected function renderAttribute( $attribute, $index )
	{
		$value = $attribute['value'] ?? null;
		
		if( $value instanceof Model || is_object( $value ) || is_array( $value ) ) {
			
			$Model = $value;
			
			$attributes = $this->normalizeAttributes( $Model );
			
			//<label class="control-label" for="productvariation-title">Title</label>
			
			$attribute['value'] = $this->renderAttributes( $attributes );
			
			if( !isset( $attribute['format'] ) || $attribute['format'] == 'text' ) {
				
				$attribute['format'] = $format = 'html';
				
			}
			
		}
		
		return parent::renderAttribute( $attribute, $index );
		
	}
	
	protected function normalizeAttributes( $Model = null, $attributes = null )
	{
		// our answer for original bungler
		if( !$Model && !$attributes ) {
			return parent::normalizeAttributes();
		}
		
		if( $attributes === null ) {
			
			if( $Model instanceof Model ) {
				$attributes = $Model->attributes();
			}
			else if( is_object( $Model ) ) {
				$attributes = $Model instanceof Arrayable ? array_keys( $Model->toArray() ) : array_keys( get_object_vars( $Model ) );
			}
			else if( is_array( $Model ) ) {
				$attributes = array_keys( $Model );
			}
			else {
				throw new InvalidConfigException( 'The "model" property must be either an array or an object.' );
			}
			
			sort( $attributes );
		}
		
		foreach( $attributes as $i => $attribute ) {
			
			if( is_string( $attribute ) ) {
			
				if( !preg_match( '/^([^:]+)(:(\w*))?(:(.*))?$/', $attribute, $matches ) ) {
					throw new InvalidConfigException( 'The attribute must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"' );
				}
			
				$attribute = [
					'attribute' => $matches[1],
					'format'    => isset( $matches[3] ) ? $matches[3] : 'text',
					'label'     => isset( $matches[5] ) ? $matches[5] : null,
				];
				
			}
			
			if( !is_array( $attribute ) ) {
				throw new InvalidConfigException( 'The attribute configuration must be an array.' );
			}
			
			if( isset( $attribute['visible'] ) && !$attribute['visible'] ) {
				unset( $attributes[ $i ] );
				continue;
			}
			
			if( isset( $attribute['attribute'] ) ) {
				
				$attributeName = $attribute['attribute'];
				
				if( !isset( $attribute['label'] ) ) {
					$attribute['label'] = $Model instanceof Model ? $Model->getAttributeLabel( $attributeName ) : Inflector::camel2words( $attributeName, true );
				}
				
				if( $attribut['value'] ?? true ) {
					$attribute['value'] = $value = ArrayHelper::getValue( $Model, $attributeName );
				}
				
			}
			else if( !isset( $attribute['label'] ) || !array_key_exists( 'value', $attribute ) ) {
				throw new InvalidConfigException( 'The attribute configuration requires the "attribute" element to determine the value and display label.' );
			}
			
			if( !isset( $attribute['format'] ) ) {
				
				if ( $value instanceof Model || is_object( $value ) || is_array( $value )  ){
					$attribute['format'] = $format = 'html';
				}
				else{
					$attribute['format'] = $format = 'text';
				}
				
			}
			
			if( $attribute['value'] instanceof \Closure ) {
				$attribute['value'] = call_user_func( $attribute['value'], $Model, $this );
			}
			
			$attributes[ $i ] = $attribute;
		}
		
		return $attributes;
	}
	
	
}