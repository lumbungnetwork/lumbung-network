<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEidrBalanceTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eidr_balance_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 12, 2);
            $table->decimal('unique_digits', 3, 0)->nullable();
            $table->tinyInteger('type')->comment('0 = withdraw, 1 = deposit');
            $table->tinyInteger('status')->comment('0 = request, 1 = transfer, 2 = done, 3 = canceled');
            $table->tinyInteger('method')->comment('1 = bank, 2 = tron');
            $table->string('tx_id', 64)->nullable();
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
        Schema::dropIfExists('eidr_balance_transactions');
    }
}
