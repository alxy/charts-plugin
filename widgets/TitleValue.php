<?php namespace Alxy\Charts\Widgets;

use Backend\Classes\WidgetBase;

class TitleValue extends WidgetBase
{
    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'titleValue';

    /**
     * @var string Title
     */
    public $title;

    /**
     * @var integer Current value
     */
    public $value;

    /**
     * @var integer Previous value
     */
    public $previousValue = null;

    /**
     * @var bool Shows a trend indicator, i.e. if the $value is greater than the $previousValue
     */
    public $showTrend = false;

    /**
     * @var string Description below the main value, %s will be replaced by $previousValue
     */
    public $description = 'previous: %s';

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
            'title',
            'value',
            'previousValue',
            'showTrend',
            'description',
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
     * Prepares the chart data
     */
    public function prepareVars()
    {
        $this->vars['cssClasses'] = implode(' ', $this->cssClasses);

        $this->vars['title'] = $this->title;
        $this->vars['value'] = $this->value;
        $this->vars['previousValue'] = $this->previousValue;
        $this->vars['valueCssClass'] = $this->getValueCssClass();
        $this->vars['description'] = $this->description;
    }

    public function getValueCssClass()
    {
        if($this->showTrend) {
            $valueClass = $this->value > $this->previousValue ? 'positive' : 'negative';
        } else {
            $valueClass = '';
        }

        return $valueClass;
    }
}