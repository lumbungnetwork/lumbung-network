<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimReward extends Migration {

    public function up() {
        Schema::create('claim_reward', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('reward_id');
            $table->date('claim_date');
            $table->smallInteger('status')->default(0)->comment('0 = belum, 1 = tuntas dr member, 2 = batal');
            $table->string('reason', 150)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('transfer_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('user_id');
            $table->index('reward_id');
            $table->index('claim_date');
            $table->index('status');
            $table->index('created_at');
            $table->index('transfer_at');
        });
    }

    public function down() {
        Schema::dropIfExists('claim_reward');
    }
}
