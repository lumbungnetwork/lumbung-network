<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReturnOnPpob extends Migration {

    public function up() {
        Schema::table('ppob', function(Blueprint $table){
            $table->text('return_buy')->nullable();
        });
    }

    public function down() {
         Schema::table('ppob', function(Blueprint $table){
            $table->dropColumn('return_buy');
        });
    }
}
