<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusReward extends Migration {

    public function up() {
        Schema::create('bonus_reward', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('is_active')->default(1)->comment('0 = tidak aktif, 1 = aktif');
            $table->integer('reward_price')->default(0);
            $table->string('reward_detail', 120)->nullable();
            $table->integer('total_kiri')->default(0);
            $table->integer('total_kanan')->default(0);
            $table->integer('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('is_active');
        });
    }

    public function down() {
        Schema::dropIfExists('bonus_reward');
    }
}
