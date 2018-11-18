<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 13.05.2018
 * Time: 15:00
 */

namespace yozh\widget\widgets\grid;

use yozh\widget\widgets\grid\DataColumn;
use yozh\widget\widgets\LimitPager;
use yozh\base\components\helpers\ArrayHelper;

class GridView extends \yii\grid\GridView
{

    public $limitPagerOptions = [
        //'limits' => LimitPager::LIMITS,
        'toggleDown' => false,
        'toggleLeft' => false,
    ];

    /**
     * @var string the default data column class if the class name is not explicitly specified when configuring a data column.
     * Defaults to 'yii\grid\DataColumn'.
     */
    public $dataColumnClass = DataColumn::class;

    public function renderTableRow($Model, $key, $index)
    {
        return parent::renderTableRow($Model, $key, $index); // TODO: Change the autogenerated stub
    }


    public function renderSection($name)
    {
        switch ($name) {
            case '{limits}':
                return $this->renderLimits();
            default:
                return parent::renderSection($name);
        }
    }

    public function renderLimits()
    {
        /* @var $class LinkPager */
        $config = $this->limitPagerOptions;

        $class = ArrayHelper::remove($limits, 'class', LimitPager::class);

        $Pagination = $this->dataProvider->getPagination();

        if ( $Pagination === false || $this->dataProvider->getCount() <= 0) {
            return '';
        }

        if( $Pagination->pageSizeLimit ){
            $config['limits'] = array_combine( $Pagination->pageSizeLimit, $Pagination->pageSizeLimit);
        }

        $config['pagination'] = $Pagination;

        return $class::widget($config);

    }
}