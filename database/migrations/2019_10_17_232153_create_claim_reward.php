<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimReward extends Migration {

    public function up() {
        Schema::create('claim_reward', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('reward_id');
            $table->date('claim_date');
            $table->smallInteger('status')->default(0);
            $table->string('reason', 150)->nullable();
            $table->timestamps();
            $table->timestamp('transfer_at')->nullable();
        });
    }

    public function down() {
        Schema::dropIfExists('claim_reward');
    }
}
