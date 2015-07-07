<?php namespace Bedard\USPS;

use System\Classes\PluginBase;

/**
 * USPS Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * @var array Plugin dependencies
     */
    public $require = ['Bedard.Shop'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'USPS Shipping',
            'description' => 'Enables shipping calculations through the U.S. Postal Service.',
            'author'      => 'Scott Bedard',
            'icon'        => 'icon-truck'
        ];
    }

}
