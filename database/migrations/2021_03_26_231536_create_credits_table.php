<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 16, 6);
            $table->tinyInteger('type')->comment('0 = debit, 1 = credit');
            $table->tinyInteger('source')->comment('1 = ref fee, 2 = withdraw, 3 = transfer, 4 = convert');
            $table->unsignedBigInteger('source_id')->default(0)->comment('user_id for fee and transfer, 0 for withdraw and convert');
            $table->string('tx_id', 64)->unique();
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
        Schema::dropIfExists('credits');
    }
}
