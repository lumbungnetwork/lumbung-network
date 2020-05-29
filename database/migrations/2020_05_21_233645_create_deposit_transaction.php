<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositTransaction extends Migration {

    public function up() {
        Schema::create('deposit_transaction', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->string('transaction_code', 25)->nullable();
            $table->smallInteger('type')->default(1)->comment('jenis transaksi. 1 =>isi deposit, 2 => tarik deposit');
            $table->decimal('price', 12, 2);
            $table->integer('unique_digit')->nullable();
            $table->integer('admin_fee')->nullable();
            $table->smallInteger('status')->default(0)->comment('0 = belum, 1 = member transfer 2 = tuntas dr admin, 3 = batal');
            $table->string('reason', 150)->nullable();
            $table->integer('user_bank')->nullable();
            $table->integer('bank_perusahaan_id')->nullable();
            $table->tinyInteger('is_tron')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('tuntas_at')->nullable();
            $table->integer('submit_by')->default(1);
            $table->timestamp('submit_at')->nullable();
            
            $table->index('user_id');
            $table->index('transaction_code');
            $table->index('type');
            $table->index('status');
            $table->index('user_bank');
            $table->index('bank_perusahaan_id');
            $table->index('is_tron');
            $table->index('created_at');
            $table->index('deleted_at');
            $table->index('tuntas_at');
            $table->index('submit_by');
            $table->index('submit_at');
        });
    }

    public function down() {
        Schema::dropIfExists('deposit_transaction');
    }
}
