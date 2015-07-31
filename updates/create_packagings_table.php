<?php namespace Bedard\USPS\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePackagingsTable extends Migration
{

    public function up()
    {
        Schema::create('bedard_usps_packagings', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_usps_packagings');
    }

}
