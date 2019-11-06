<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsVIPOnUsers extends Migration {

    public function up() {
        Schema::table('users', function(Blueprint $table){
            $table->tinyInteger('is_vip')->default(0);
            $table->index('is_vip');
        });
    }

    public function down() {
        Schema::table('users', function(Blueprint $table){
            $table->dropColumn('is_vip');
        });
    }
}
