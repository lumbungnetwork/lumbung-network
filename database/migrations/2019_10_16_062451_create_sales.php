<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSales extends Migration {

    public function up() {
        Schema::create('sales', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('stockist_id');
            $table->unsignedBigInteger('purchase_id');
            $table->string('invoice', 40);
            $table->decimal('amount', 8, 2);
            $table->decimal('sale_price', 12, 2);
            $table->date('sale_date');
            $table->string('reason', 175)->nullable();
            $table->unsignedBigInteger('master_sales_id');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('sales');
    }
}
