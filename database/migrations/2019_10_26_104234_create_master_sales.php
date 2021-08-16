<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterSales extends Migration {

    public function up() {
        Schema::create('master_sales', function (Blueprint $table) {
            $table->id('id');
            $table->integer('user_id')->unsigned();
            $table->integer('stockist_id')->unsigned();
            $table->string('invoice', 40);
            $table->decimal('total_price', 12, 2);
            $table->date('sale_date');
            $table->smallInteger('status')->default(0);
            $table->string('reason', 175)->nullable();
            $table->smallInteger('buy_metode')->default(0);
            $table->string('tron', 200)->nullable();
            $table->string('tron_transfer', 200)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_no', 50)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('master_sales');
    }
}
