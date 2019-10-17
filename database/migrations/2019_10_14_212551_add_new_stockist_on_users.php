<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewStockistOnUsers extends Migration {

    public function up() {
        Schema::table('users', function(Blueprint $table){
            $table->string('kecamatan', 175)->nullable();
            $table->string('kelurahan', 175)->nullable();
            $table->tinyInteger('is_stockist')->default(0);
            $table->timestamp('stockist_at')->nullable();
            
            $table->index('is_stockist');
            $table->index('stockist_at');
        });
    }

    public function down() {
        Schema::table('users', function(Blueprint $table){
            $table->dropColumn('kecamatan');
            $table->dropColumn('kelurahan');
            $table->dropColumn('is_stockist');
            $table->dropColumn('stockist_at');
        });
    }
}
