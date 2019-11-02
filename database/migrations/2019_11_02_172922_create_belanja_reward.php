<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBelanjaReward extends Migration {

    public function up() {
        Schema::create('belanja_reward', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('reward');
            $table->integer('month');
            $table->integer('year');
            $table->date('belanja_date');
            $table->decimal('total_belanja', 12, 2);
            $table->smallInteger('status')->default(0)->comment('0 = belum, 1 = tuntas, 2 = batal');
            $table->string('reason', 150)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('tuntas_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('user_id');
            $table->index('month');
            $table->index('year');
            $table->index('belanja_date');
            $table->index('status');
            $table->index('created_at');
            $table->index('tuntas_at');
            $table->index('reward');
        });
    }

    public function down() {
        Schema::dropIfExists('belanja_reward');
    }
}
