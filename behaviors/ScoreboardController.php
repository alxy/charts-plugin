<?php namespace Alxy\Charts\Behaviors;

use Alxy\Charts\Widgets\ScoreBoard;
use ApplicationException;
use Backend\Classes\ControllerBehavior;
use SystemException;
use Symfony\Component\Yaml\Exception\ParseException;
use Exception;

/**
 * Adds features for a scoreboard including different
 * types of charts to a controller for quick insights.
 * 
 * This behavior is implemented in the controller like so:
 *
 *     public $implement = [
 *         'Alxy.Charts.Behaviors.ScoreboardController',
 *     ];
 *
 *     public $scoreboardConfig = 'config_scoreboard.yaml';
 *
 * The `$scoreboardConfig` property makes reference to the scoreboard configuration
 * values as either a YAML file, located in the controller view directory,
 * or directly as a PHP array.
 * 
 */
class ScoreboardController extends ControllerBehavior
{
    /**
     * @var \October\Rain\Database\Model Reference to the model object.
     */
    protected $model;

    /**
     * @var ScoreBoard Reference to the widget object.
     */
    protected $scoreboardWidget;

    /**
     * @var array Chart definitions
     */
    protected $chartDefinitions;

    /**
     * @var array Required configuraton values
     * - modelClass: Class name for the model
     * - charts: Chart definitions
     */
    protected $requiredConfig = ['modelClass', 'charts'];

    /**
     * @inheritDoc
     */
    protected $requiredProperties = ['scoreboardConfig'];

    /**
     * Behavior constructor
     * 
     * @param \Backend\Classes\Controller $controller
     */
    public function __construct($controller)
    {
        parent::__construct($controller);

        /*
         * Build configuration
         */
        $this->config = $this->makeConfig($controller->scoreboardConfig, $this->requiredConfig);
        $this->model = $this->createModel();
        $this->chartDefinitions = $this->buildChartDefinitions();

        $this->initScoreboard($this->chartDefinitions);
    }

    /**
     * Renders the scoreboard
     * 
     * @return string 
     * @throws SystemException 
     * @throws ParseException 
     * @throws ApplicationException 
     * @throws Exception 
     */
    public function scoreboardRender()
    {
        return $this->scoreboardWidget->render();
    }

    public function initScoreboard($chartDefinitions)
    {
        $this->scoreboardWidget = $this->makeWidget(ScoreBoard::class, [
            'charts' => $chartDefinitions
        ]);
        $this->scoreboardWidget->bindToController();
    }

   /**
     * Internal method used to prepare the model object.
     *
     * @return \October\Rain\Database\Model
     */
    protected function createModel()
    {
        $class = $this->config->modelClass;
        return new $class;
    }

    protected function buildChartDefinitions()
    {
        $definitions = $this->config->charts;

        foreach ($definitions as $alias => &$definition) {
            switch ($definition['type']) {
                case 'piechart':
                case 'barchart':
                case 'linechart':
                    $definition['data'] = $this->loadArrayValue($definition['data']);
                    break;
                case 'titlevalue':
                    $definition['value'] = $this->loadScalarValue($definition['value']);
                    $definition['previousValue'] = $this->loadScalarValue($definition['previousValue']);
                case 'goalmeter':
                    $definition['value'] = $this->loadScalarValue($definition['value']);
                    $definition['goal'] = $this->loadScalarValue($definition['goal']);                
                default:
                    break;
            }
        }

        return $definitions;
    }

    protected function loadArrayValue($value) {
        if(is_array($value)) {
            return $value;
        } elseif($this->model->methodExists($value)) {
            return $this->model->{$value}();
        } else {
            throw new ApplicationException("Invalid definition specified.");
        }
    }

    protected function loadScalarValue($value) {
        if(is_numeric($value)) {
            return $value;
        } elseif($this->model->propertyExists($value)) {
            return $this->model->{$value};
        } else {
            throw new ApplicationException("Invalid definition specified.");
        }
    }
}