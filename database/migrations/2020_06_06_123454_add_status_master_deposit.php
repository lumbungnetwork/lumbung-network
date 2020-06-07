<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusMasterDeposit extends Migration {

    public function up() {
        Schema::table('master_deposit', function(Blueprint $table){
            $table->smallInteger('status')->default(0)->comment('0 = belum tuntas, 1 => Tuntas');
            $table->index('status');
        });
    }

    public function down() {
        Schema::table('master_deposit', function(Blueprint $table){
            $table->dropColumn('status');
        });
    }
}
