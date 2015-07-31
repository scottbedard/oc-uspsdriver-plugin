<?php namespace Bedard\USPS;

use Bedard\Shop\Controllers\Products;
use Bedard\Shop\Models\Product;
use Bedard\USPS\Models\Packaging;
use Event;
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

    /**
     * Extend the product model with a packaging field
     * @return [type] [description]
     */
    public function boot()
    {

        Product::extend(function($model)
        {
            $model->belongsTo['packaging'] = ['Bedard\USPS\Models\Packaging'];
        });

        Event::listen('backend.form.extendFields', function($widget)
        {
            if (!$widget->getController() instanceof Products) return;
            if (!$widget->model instanceof Product) return;

            $widget->addSecondaryTabFields([
                'packaging_id' => [
                    'tab'       => 'bedard.shop::lang.products.details_tab',
                    'label'     => 'Packaging',
                    'comment'   => 'Select the smallest packaging this product can be mailed in.',
                    'type'      => 'dropdown',
                    'options'   => Packaging::all()->lists('name', 'id'),
                    'span'      => 'right',
                ],
            ]);
        });
    }

}
