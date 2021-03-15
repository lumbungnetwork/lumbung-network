<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLmbDividend extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lmb_dividend', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 12, 2);
            $table->tinyInteger('type')->comment('1 = Stockist, 2 = Vendor, 3 = Digital, 4 = Membership, 5 = Lending Fee');
            $table->tinyInteger('status')->comment('0 = Deduction, 1 = Addition');
            $table->integer('source_id');
            $table->unique(['type', 'source_id', 'status', 'amount']);
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
        Schema::dropIfExists('table_lmb_dividend');
    }
}
