<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnstakeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unstake', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('staking_id');
            $table->mediumInteger('user_id');
            $table->decimal('amount', 15, 6);
            $table->timestamp('due_date');
            $table->tinyInteger('status')->default(0);
            $table->unique(['staking_id', 'user_id']);
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
        Schema::dropIfExists('unstake');
    }
}
