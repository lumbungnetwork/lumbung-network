<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->decimal('amount', 8, 2);
            $table->tinyInteger('type')->comment('0 = debit, 1 = credit');
            $table->tinyInteger('action')->comment('0 = withdraw, 1 = compound')->nullable();
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
        Schema::dropIfExists('yields');
    }
}
