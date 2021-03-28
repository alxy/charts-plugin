<?php namespace Alxy\Charts;

use Backend;
use System\Classes\PluginBase;

/**
 * Charts Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Charts',
            'description' => 'Provides charts and scoreboard functionalities.',
            'author'      => 'Alxy',
            'icon'        => 'icon-leaf'
        ];
    }
}
