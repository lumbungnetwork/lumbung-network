<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIstronOnTransferWD extends Migration {

    public function up() {
        Schema::table('transfer_wd', function(Blueprint $table){
            $table->tinyInteger('is_tron')->default(0);
            $table->index('is_tron');
        });
    }

    public function down() {
        Schema::table('transfer_wd', function(Blueprint $table){
            $table->dropColumn('is_tron');
        });
    }
}
