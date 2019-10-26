<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdMasterSalesOnSales extends Migration {

    public function up() {
        Schema::table('sales', function(Blueprint $table){
            $table->integer('master_sales_id')->nullable()->unsigned();
            $table->index('master_sales_id');
        });
    }

    public function down() {
        Schema::table('sales', function(Blueprint $table){
            $table->dropColumn('master_sales_id');
        });
    }
}
