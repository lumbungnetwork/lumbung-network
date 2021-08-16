<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockistRequest extends Migration {

    public function up() {
        Schema::create('stockist_request', function (Blueprint $table) {
            $table->id('id');
            $table->integer('user_id');
            $table->smallInteger('status')->default(0);
            $table->timestamp('active_at')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('stockist_request');
    }
}
