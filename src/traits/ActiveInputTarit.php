<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 10.05.2018
 * Time: 17:58
 */

namespace yozh\widget\traits;

use yii\base\Model;
use yii\helpers\Html;

/**
 * InputWidget is the base class for all jQuery UI input widgets.
 *
 * Classes extending from this widget can be used in an [[yii\widgets\ActiveForm|ActiveForm]]
 * using the [[yii\widgets\ActiveField::widget()|widget()]] method, for example like this:
 *
 * ```php
 * <?= $form->field($Model, 'from_date')->widget('WidgetClassName', [
 *     // configure additional widget properties here
 * ]) ?>
 * ```
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
trait ActiveInputTarit
{
	use WidgetTrait {
		init as private _init;
	}
	
	/**
	 * @var Model the data model that this widget is associated with.
	 */
	public $model;
	
	/**
	 * @var string the model attribute that this widget is associated with.
	 */
	public $attribute;
	
	/**
	 * @var string the input name. This must be set if [[model]] and [[attribute]] are not set.
	 */
	public $name;
	
	/**
	 * @var string the input value.
	 */
	public $value;
	
	
	/**
	 * Initializes the widget.
	 * If you override this method, make sure you call the parent implementation first.
	 */
	public function init()
	{
		if ($this->hasModel() && !isset($this->options['id'])) {
			$this->options['id'] = Html::getInputId($this->model, $this->attribute);
		}
		
		$this->_init();
	}
	
	/**
	 * @return bool whether this widget is associated with a data model.
	 */
	protected function hasModel()
	{
		return $this->model instanceof Model && $this->attribute !== null;
	}
}
