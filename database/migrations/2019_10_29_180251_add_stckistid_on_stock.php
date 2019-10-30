<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStckistidOnStock extends Migration {

    public function up() {
        Schema::table('stock', function(Blueprint $table){
            $table->integer('stockist_id')->nullable()->unsigned();
            $table->index('stockist_id');
        });
    }

    public function down() {
        Schema::table('stock', function(Blueprint $table){
            $table->dropColumn('stockist_id');
        });
    }
}
