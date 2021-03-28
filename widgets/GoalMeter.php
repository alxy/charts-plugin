<?php namespace Alxy\Charts\Widgets;

use Backend\Classes\WidgetBase;

class GoalMeter extends WidgetBase
{
    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'goalMeter';

    /**
     * @var string Title
     */
    public $title;

    /**
     * @var integer Current value
     */
    public $value;

    /**
     * @var integer Goal value, must be greater than zero
     */
    public $goal = 100;

    /**
     * @var string Description below the main value, %s will be replaced by ($value - $goal)
     */
    public $description = 'remaining: %s';

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
            'goal',
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
        $this->vars['goal'] = $this->goal;
        $this->vars['remaining'] = $this->goal - $this->value;
        $this->vars['percentage'] = round($this->value / $this->goal * 100);
        $this->vars['description'] = $this->description;
    }
}