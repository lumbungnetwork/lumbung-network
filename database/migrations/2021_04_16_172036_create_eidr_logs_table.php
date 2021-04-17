<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEidrLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eidr_logs', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 12, 2);
            $table->string('from', 34);
            $table->string('to', 34);
            $table->string('hash', 64)->unique();
            $table->tinyInteger('type')->comment('1 = Konversi bonus, 2 = Dividen LMB, 3 = Topup eIDR, 4 = WD saldo Vendor, 5 = Deposit saldo Vendor');
            $table->string('detail', 100);
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
        Schema::dropIfExists('eidr_logs');
    }
}
