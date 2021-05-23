<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEidrBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eidr_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 12, 2);
            $table->tinyInteger('type');
            $table->tinyInteger('source')->comment('0 = outflow, 1 = LMBdiv, 2 = Sponsor, 3 = Binary, 4 = IDR deposit, 5 = eIDR deposit, 6 = Sales');
            $table->string('tx_id', 70)->nullable();
            $table->string('note', 100)->nullable();
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
        Schema::dropIfExists('eidr_balances');
    }
}
