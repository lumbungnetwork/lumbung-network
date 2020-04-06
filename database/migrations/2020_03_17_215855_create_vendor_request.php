<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorRequest extends Migration {

    public function up() {
        Schema::create('vendor_request', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->smallInteger('status')->default(0)->comment('0 = request, 1 = aktif, 2 = batal');
            $table->timestamp('active_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('user_id');
            $table->index('status');
            $table->index('active_at');
            $table->index('created_at');
        });
    }

    public function down() {
        Schema::dropIfExists('vendor_request');
    }
}
