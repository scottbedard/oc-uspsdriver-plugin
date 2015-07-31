<?php namespace Bedard\USPS\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddPackagingColumn extends Migration
{

    public function up()
    {
        Schema::table('bedard_shop_products', function($table)
        {
            $table->integer('packaging_id')->unsigned()->default(0);
        });
    }

    public function down()
    {
        Schema::table('bedard_shop_products', function($table)
        {
            $table->dropColumn('packaging_id');
        });
    }

}
