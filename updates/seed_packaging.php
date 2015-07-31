<?php namespace Bedard\Shop\Updates;

use Bedard\USPS\Models\Packaging;
use Lang;
use October\Rain\Database\Updates\Seeder;

class SeedPackaging extends Seeder
{
    public function run()
    {
        Packaging::create(['name' => 'Letter']);
        Packaging::create(['name' => 'Padded Envelope']);
        Packaging::create(['name' => 'Box']);
    }
}
