<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermisionOnUsers extends Migration {

    public function up() {
        Schema::table('users', function(Blueprint $table){
            $table->string('permission', 200)->nullable();
            $table->index('permission');
        });
    }

    public function down() {
        Schema::table('users', function(Blueprint $table){
            $table->dropColumn('permission');
        });
    }
}
