<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusReward2 extends Migration {

    public function up() {
        Schema::create('bonus_reward2', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('is_active')->default(1)->comment('0 = tidak aktif, 1 = aktif');
            $table->string('name', 50)->nullable();
            $table->string('reward_detail', 120)->nullable();
            $table->string('image', 100)->nullable();
            $table->integer('qualified')->default(0);
            $table->integer('member_type')->default(0);
            $table->integer('type')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('is_active');
        });
    }

    public function down() {
        Schema::dropIfExists('bonus_reward2');
    }
}
