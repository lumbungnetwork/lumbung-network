<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSales extends Migration {

    public function up() {
        Schema::create('sales', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('stockist_id')->unsigned();
            $table->smallInteger('is_stockist')->default(0)->comment('0 = request, 1 = aktif, 2 = batal');
            $table->integer('purchase_id')->unsigned();
            $table->string('invoice', 40);
            $table->decimal('amount', 8, 2);
            $table->decimal('sale_price', 12, 2);
            $table->date('sale_date');
            $table->string('reason', 175)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('user_id');
            $table->index('stockist_id');
            $table->index('is_stockist');
            $table->index('purchase_id');
            $table->index('invoice');
            $table->index('amount');
            $table->index('sale_date');
            $table->index('created_at');
            $table->index('deleted_at');
        });
    }

    public function down() {
        Schema::dropIfExists('sales');
    }
}
