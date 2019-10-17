<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCodeDaerahOnUsers extends Migration {

    public function up() {
        Schema::table('users', function(Blueprint $table){
            $table->string('kode_daerah', 25)->nullable();
            
            $table->index('kode_daerah');
        });
    }

    public function down() {
        Schema::table('users', function(Blueprint $table){
            $table->dropColumn('kode_daerah');
        });
    }
}
