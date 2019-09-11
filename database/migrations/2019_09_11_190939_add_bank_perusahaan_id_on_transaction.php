<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBankPerusahaanIdOnTransaction extends Migration {

    public function up() {
        Schema::table('transaction', function(Blueprint $table){
            $table->integer('bank_perusahaan_id')->nullable();
            $table->index('bank_perusahaan_id');
        });
    }

    public function down() {
        Schema::table('transaction', function(Blueprint $table){
            $table->dropColumn('bank_perusahaan_id');
        });
    }
}
