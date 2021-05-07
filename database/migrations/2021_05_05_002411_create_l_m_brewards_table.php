<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLMBrewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lmb_rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 6, 3);
            $table->decimal('sales', 12, 2)->nullable();
            $table->tinyInteger('type')->default(1)->comment('0 = claimed, 1 = credited');
            $table->tinyInteger('is_store')->default(0)->comment('0 = Shopping Reward, 1 = Selling Reward');
            $table->string('hash', 64)->nullable();
            $table->timestamp('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lmb_rewards');
    }
}
