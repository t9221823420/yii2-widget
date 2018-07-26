<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 21.04.2018
 * Time: 15:59
 */

namespace yozh\widget\widgets\grid;

class ActionColumn extends \yii\grid\ActionColumn
{
	public $filter;
	
	protected function renderFilterCellContent()
	{
		return $this->filter;
	}
}