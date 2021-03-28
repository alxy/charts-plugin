<?php namespace Alxy\Charts\Widgets;

use Backend\Classes\WidgetBase;

class BarChart extends WidgetBase
{
    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'barChart';

    /**
     * @var array Chart data of format [['label' => 'Label 1', 'value' => 100], ['label' => 'Label 2', 'value' => 200]]
     */
    public $data;

    /**
     * @var bool Display legends centered below the graph
     */
    public $centered = false;

    /**
     * @var bool Display legends in one row 
     */
    public $wrapLegend = false;

    /**
     * @var array List of CSS classes to apply to the chart container
     */
    public $cssClasses = [];

    /**
     * Initialize the widget, called by the constructor and free from its parameters.
     */
    public function init()
    {
        $this->fillFromConfig([
            'data',
            'centered',
            'wrapLegend',
            'cssClasses'
        ]);
    }

    /**
     * Renders the widget.
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('chart');
    }

    /**
     * Prepares the list data
     */
    public function prepareVars()
    {
        $this->vars['cssClasses'] = $this->getCssClasses();
        $this->vars['data'] = $this->data;
    }

    /**
     * Returns a string of CSS classes to apply to the chart container
     * @return string CSS classes
     */
    public function getCssClasses()
    {
        $cssClasses = $this->cssClasses;

        if($this->centered) {
            $cssClasses[] = 'centered';
        }

        if($this->wrapLegend) {
            $cssClasses[] = 'wrap-legend';
        }

        return implode(' ', $cssClasses);
    }
}