<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersDividend extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_dividend', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('user_id');
            $table->tinyInteger('type')->comment('0 = Out, 1 = In');
            $table->decimal('amount', 8, 2)->comment('0 = Claimed, 1 = Rewarded');
            $table->timestamp('date');
            $table->string('hash', 64)->nullable();
            $table->unique(['user_id', 'type', 'amount', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_dividend');
    }
}
