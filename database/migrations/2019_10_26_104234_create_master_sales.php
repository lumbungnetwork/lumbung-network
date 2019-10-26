<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterSales extends Migration {

    public function up() {
        Schema::create('master_sales', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('stockist_id')->unsigned();
            $table->smallInteger('is_stockist')->default(0)->comment('0 = request, 1 = aktif, 2 = batal');
            $table->string('invoice', 40);
            $table->decimal('total_price', 12, 2);
            $table->date('sale_date');
            $table->smallInteger('status')->default(0)->comment('0 = belum, 1 = konfirmasi member, 2 = konfirmasi admin, 3 = batal');
            $table->string('reason', 175)->nullable();
            $table->smallInteger('buy_metode')->default(0)->comment('1 = COD, 2 = Transfer Bank, 3 = Tron');
            $table->string('tron', 200)->nullable();
            $table->string('tron_transfer', 200)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_no', 50)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('user_id');
            $table->index('stockist_id');
            $table->index('is_stockist');
            $table->index('invoice');
            $table->index('total_price');
            $table->index('sale_date');
            $table->index('status');
            $table->index('buy_metode');
            $table->index('created_at');
            $table->index('deleted_at');
        });
    }

    public function down() {
        Schema::dropIfExists('master_sales');
    }
}
