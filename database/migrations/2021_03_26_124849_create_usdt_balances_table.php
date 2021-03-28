<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUSDTbalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usdt_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Belongs to Finance model.
            $table->decimal('amount', 16, 6);
            $table->tinyInteger('type')->comment('0 = debit, 1 = credit');
            $table->tinyInteger('status')->comment('0 = pending, 1 = final')->default(0);
            $table->string('hash', 64)->nullable()->unique();
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
        Schema::dropIfExists('usdt_balances');
    }
}
