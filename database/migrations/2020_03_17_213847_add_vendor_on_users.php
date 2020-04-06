<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVendorOnUsers extends Migration {

    public function up() {
        Schema::table('users', function(Blueprint $table){
            $table->tinyInteger('is_vendor')->default(0);
            $table->timestamp('vendor_at')->nullable();
            
            $table->index('is_vendor');
            $table->index('vendor_at');
        });
    }

    public function down() {
        Schema::table('users', function(Blueprint $table){
            $table->dropColumn('is_vendor');
            $table->dropColumn('vendor_at');
        });
    }
}
