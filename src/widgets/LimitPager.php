<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 14.04.2018
 * Time: 19:50
 */

namespace yozh\widget\widgets;

use yozh\widget\widgets\BaseJuiWidget as Widget;
use yii\data\Pagination;
use yii\helpers\Html;
use yozh\base\components\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yozh\widget\AssetBundle;

class LimitPager extends Widget
{
	
	const LIMITS = [ 10, 25, 50, 100, 500 ];
	
	/**
	 * @var array the HTML attributes for the container tag. The following special options are recognized:
	 *
	 * - tag: string, defaults to "div", the name of the container tag.
	 *
	 * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
	 * @since 2.0.1
	 */
	public $containerOptions = [];
	
	public $dropdownOptions = [];
	
	public $toggleDown = true;
	public $toggleLeft = true;
	
	/**
	 * @var array the HTML attributes of the button.
	 * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
	 */
	public $options = [];
	
	/**
	 * @var string name of a class to use for rendering dropdowns withing this widget. Defaults to [[Dropdown]].
	 * @since 2.0.7
	 */
	public $dropdownClass = \yii\bootstrap\Dropdown::class;
	
	public $limits;
	
	public $caret = '<span class="caret"></span>';
	
	/**
	 * @var Pagination the pagination object that this pager is associated with.
	 * You must set this property in order to make LinkPager work.
	 */
	public $pagination;
	
	/**
	 * @var bool Hide widget when only one page exist.
	 */
	public $hideOnSinglePage = true;
	
	public function init()
	{
		
		$this->limits = $this->limits ?? array_combine( static::LIMITS, static::LIMITS );
		
		\Yii::$app->assetManager->getBundle( AssetBundle::class )->css[] = 'css/yozh-widget-limitpager.css';
		
		parent::init();
		
		if( !$this->pagination instanceof Pagination ) {
			throw new InvalidConfigException( 'The "pagination" property have to be an instance of yii\data\Pagination.' );
		}
		
	}
	
	public function run()
	{
		$pageCount = $this->pagination->getPageCount();
		
		if( $pageCount < 2 && $this->hideOnSinglePage ) {
			return '';
		}
		
		Html::addCssClass( $this->containerOptions, [ 'type' => 'pagination-limits ' . ( $this->toggleDown === true ? 'dropdown' : 'dropup' ) ] );
		
		$options = $this->containerOptions;
		$tag     = ArrayHelper::remove( $options, 'tag', 'div' );
		
		$this->toggleLeft === true ?: $this->dropdownOptions['options']['class'] = 'dropdown-menu-right';
		
		return implode( "\n", [
			Html::beginTag( $tag, $options ),
			$this->_renderButton(),
			$this->_renderDropdown(),
			Html::endTag( $tag ),
		] );
		
	}
	
	protected function _renderButton()
	{
		$this->options['data-toggle'] = 'dropdown';
		
		Html::addCssClass( $this->options, [ 'toggle' => 'btn btn-default dropdown-toggle' ] );
		
		$label = $this->pagination->getPageSize() . " {$this->caret}";
		
		$tag = ArrayHelper::remove( $this->options, 'tag', 'div' );
		$id  = ArrayHelper::remove( $this->options, 'id' );
		
		return Html::tag( $tag, $label, $this->options );
	}
	
	protected function _renderDropdown()
	{
		
		$currentPage = $this->pagination->getPage();
		
		$items = [];
		foreach( $this->limits as $value => $label ) {
			$items[] = [
				'label' => $label,
				'url'   => $this->pagination->createUrl( $currentPage, $value ),
			];
		}
		
		$config = $this->dropdownOptions;
		
		$config['items']         = $items;
		$config['options']['id'] = false;
		
		/** @var Widget $dropdownClass */
		$dropdownClass = $this->dropdownClass;
		
		return $dropdownClass::widget( $config );
	}
	
}