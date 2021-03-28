<?php namespace Alxy\Charts\Widgets;

use Backend\Classes\WidgetBase;

class LineChart extends WidgetBase
{
    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'lineChart';

    /**
     * @var array Chart data of format [['x' => 1, 'y' => 100], ['x' => 2, 'y' => 200]]
     */
    public $data;

    /**
     * Formating options for the x axis
     * 
     * @var array X axis options
     * @see https://github.com/flot/flot/blob/master/API.md#customizing-the-axes
     */
    public $xAxisOptions = [];

    /**
     * Formating options for the y axis
     * 
     * @var array Y axis options
     * @see https://github.com/flot/flot/blob/master/API.md#customizing-the-axes
     */
    public $yAxisOptions = [];

    /**
     * 
     * @var string If the "weeks" value is specified and the X axis mode is "time", the X axis labels will be displayed as week end dates.
     */
    public $timeMode = 'weeks';

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
            'xAxisOptions',
            'yAxisOptions',
            'timeMode',
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
        $this->vars['xAxisOptions'] = json_encode($this->xAxisOptions, JSON_FORCE_OBJECT);
        $this->vars['yAxisOptions'] = json_encode($this->yAxisOptions, JSON_FORCE_OBJECT);
        $this->vars['timeMode'] = $this->timeMode;
        $this->vars['data'] = $this->data;
    }

    /**
     * Returns a string of CSS classes to apply to the chart container
     * 
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

    /**
     * Returns the dataset data in correct format
     * 
     * @return string 
     */
    public function getDatasetData()
    {
        return collect($this->data)->map(function($row) {
            return sprintf('[%s, %s]', $row['x'], $row['y']);
        })->implode(', ');
    }
}