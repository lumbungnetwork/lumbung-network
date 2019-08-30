<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RepeatOrder extends Migration {

    public function up() {
        Schema::create('repeat_order', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->smallInteger('package_id');
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('user_id');
            $table->index('package_id');
            $table->index('created_at');
        });
    }

    public function down() {
        Schema::dropIfExists('repeat_order');
    }
}
