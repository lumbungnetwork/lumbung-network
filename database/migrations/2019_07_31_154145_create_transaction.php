<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaction extends Migration {

    public function up() {
        Schema::create('transaction', function (Blueprint $table) { //member aktif beli pin kepada perusahaan
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->string('transaction_code', 25)->nullable();
            $table->smallInteger('type')->default(1)->comment('jenis transaksi. 1 => beli pin, 10 => Repeat Order, 20 => Upgrade');
            $table->integer('total_pin')->nullable()->comment('total pin yang dibeli');
            $table->decimal('price', 12, 2);
            $table->integer('unique_digit');
            $table->smallInteger('status')->default(0)->comment('0 = belum, 1 = member transfer 2 = tuntas dr admin, 3 = batal');
            $table->string('reason', 150)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('tuntas_at')->nullable();
            
            $table->index('user_id');
            $table->index('transaction_code');
            $table->index('type');
            $table->index('total_pin');
            $table->index('status');
            $table->index('created_at');
            $table->index('deleted_at');
            $table->index('tuntas_at');
        });
    }

    public function down() {
        Schema::dropIfExists('transaction');
    }
}
