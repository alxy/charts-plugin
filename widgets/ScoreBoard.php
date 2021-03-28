<?php namespace Alxy\Charts\Widgets;

use ApplicationException;
use Backend\Classes\WidgetBase;
use Backend\Classes\WidgetManager;
use Lang;
use SystemException;
use Symfony\Component\Yaml\Exception\ParseException;

class ScoreBoard extends WidgetBase
{
    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'scoreBoard';

    /**
     * @var array Chart definitions of format ['chartAlias' => ['config' => 'value]]
     */
    public $charts;

    /**
     * @var array List of CSS classes to apply to the chart container
     */
    public $cssClasses = [];

    /**
     * @var array Registered chart widgets
     */
    protected $chartWidgets;

    /**
     * @var string[] Map of chart types to class names
     */
    protected static $widgetTypeClassMap = [
        'piechart' => PieChart::class,
        'barchart' => BarChart::class,
        'linechart' => LineChart::class,
        'titlevalue' => TitleValue::class,
        'goalmeter' => GoalMeter::class
    ];

    /**
     * Initialize the widget, called by the constructor and free from its parameters.
     */
    public function init()
    {
        $this->fillFromConfig([
            'charts',
            'cssClasses'
        ]);
    }

    /**
     * Renders the widget.
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('scoreboard');
    }

    /**
     * Prepares the scoreboard data
     */
    public function prepareVars()
    {
        $this->vars['cssClasses'] = implode(' ', $this->cssClasses);
        $this->vars['charts'] = $this->defineCharts();
    }

    /**
     * Initialize the chart widgets
     * 
     * @return array 
     * @throws SystemException 
     * @throws ParseException 
     * @throws ApplicationException 
     */
    protected function defineCharts()
    {
        foreach ($this->charts as $alias => $config) {
            $widget = $this->makeChartWidget($config, $alias);
            $widget->bindToController();
        }

        return $this->chartWidgets;
    }

    /**
     * Create a chart widget from the supplied configuration array
     * 
     * @param array $config 
     * @param string $alias 
     * @return WidgetBase 
     * @throws SystemException 
     * @throws ParseException 
     * @throws ApplicationException 
     */
    protected function makeChartWidget($config, $alias)
    {
        $widgetConfig = $this->makeConfig($config);
        $widgetConfig->alias = $this->alias . studly_case($alias);
        $widgetConfig->cssClasses = ['scoreboard-item'];

        $widgetClass = array_get(self::$widgetTypeClassMap, $widgetConfig->type, $widgetConfig->type);

        if (!class_exists($widgetClass)) {
            throw new ApplicationException(Lang::get(
                'backend::lang.widget.not_registered',
                ['name' => $widgetClass]
            ));
        }

        $widget = $this->makeWidget($widgetClass, $widgetConfig);
        return $this->chartWidgets[$alias] = $widget;
    }
}