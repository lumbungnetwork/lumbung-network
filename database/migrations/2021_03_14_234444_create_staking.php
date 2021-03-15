<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staking', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('user_id');
            $table->tinyInteger('type')->comment('1 = Stake, 2 = Unstake, 3 = Claim Dividend');
            $table->decimal('amount', 15, 6);
            $table->string('hash', 64)->nullable();
            $table->unique(['amount', 'hash']);
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
        Schema::dropIfExists('staking');
    }
}
