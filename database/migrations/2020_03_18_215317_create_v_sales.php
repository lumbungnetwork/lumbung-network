<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVSales extends Migration {

    public function up() {
        Schema::create('vsales', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('vendor_id')->unsigned();
            $table->smallInteger('is_vendor')->default(0)->comment('0 = request, 1 = aktif, 2 = batal');
            $table->integer('purchase_id')->unsigned();
            $table->string('invoice', 40);
            $table->decimal('amount', 8, 2);
            $table->decimal('sale_price', 12, 2);
            $table->date('sale_date');
            $table->string('reason', 175)->nullable();
            $table->integer('vmaster_sales_id')->nullable()->unsigned();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('user_id');
            $table->index('vendor_id');
            $table->index('is_vendor');
            $table->index('purchase_id');
            $table->index('invoice');
            $table->index('amount');
            $table->index('sale_date');
            $table->index('vmaster_sales_id');
            $table->index('created_at');
            $table->index('deleted_at');
        });
    }

    public function down() {
        Schema::dropIfExists('vsales');
    }
}
