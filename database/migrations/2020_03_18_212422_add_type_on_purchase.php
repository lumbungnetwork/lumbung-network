<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeOnPurchase extends Migration {

    public function up() {
        Schema::table('purchase', function(Blueprint $table){
            $table->smallInteger('type')->default(1)->unsigned();
            
            $table->index('type');
        });
    }

    public function down() {
        Schema::table('purchase', function(Blueprint $table){
            $table->dropColumn('type');
        });
    }
}
