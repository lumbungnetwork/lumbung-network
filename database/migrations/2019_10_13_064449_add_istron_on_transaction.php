<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIstronOnTransaction extends Migration {

    public function up() {
        Schema::table('transaction', function(Blueprint $table){
            $table->tinyInteger('is_tron')->default(0);
            $table->index('is_tron');
        });
    }

    public function down() {
        Schema::table('transaction', function(Blueprint $table){
            $table->dropColumn('is_tron');
        });
    }
}
