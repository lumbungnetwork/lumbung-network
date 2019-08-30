<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBinaryHistory extends Migration {

    public function up() {
        Schema::create('binary_history', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('total_left');
            $table->integer('total_right');
            $table->date('binary_date');
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('user_id');
            $table->index('total_left');
            $table->index('total_right');
            $table->index('binary_date');
            $table->index('created_at');
        });
    }

    public function down() {
        Schema::dropIfExists('binary_history');
    }
}
