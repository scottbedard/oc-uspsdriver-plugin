<?php namespace Bedard\Shop\Updates;

use Bedard\Shop\Models\Driver;
use System\Models\File;
use October\Rain\Database\Updates\Seeder;

class Install extends Seeder
{
    public function run()
    {
        $driver = Driver::firstOrCreate([
            'name'              => 'U.S. Postal Service',
            'type'              => 'shipping',
            'class'             => 'Bedard\USPS\Classes\USPS',
            'is_configurable'   => true,
            'is_default'        => false,
        ]);

        $logo = new File;
        $logo->fromFile(plugins_path('bedard/usps/assets/images/usps.png'));
        $logo->save();
        $driver->image()->add($logo);
    }
}
