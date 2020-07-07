<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsVendorConfirmOnPpob extends Migration {

    public function up() {
        Schema::table('ppob', function(Blueprint $table){
            $table->smallInteger('vendor_approve')->default(0)->comment('1 = Pending, 2 => Tuntas, 3 => gagal');
            $table->text('vendor_cek')->nullable();
        });
    }

    public function down() {
        Schema::table('ppob', function(Blueprint $table){
            $table->dropColumn('vendor_approve');
            $table->dropColumn('vendor_cek');
        });
    }
}
