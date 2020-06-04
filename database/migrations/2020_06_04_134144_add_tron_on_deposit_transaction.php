<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTronOnDepositTransaction extends Migration {

    public function up() {
        Schema::table('deposit_transaction', function(Blueprint $table){
            $table->string('tron_transfer', 200)->nullable();
        });
    }

    public function down() {
        Schema::table('deposit_transaction', function(Blueprint $table){
            $table->dropColumn('tron_transfer');
        });
    }
}
