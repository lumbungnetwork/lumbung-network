<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXMasterSales extends Migration {

    public function up() {
        Schema::create('x_master_sales', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('vendor_id')->unsigned();
            $table->smallInteger('type_pobx')->comment('1 => Pulsa, 2 => Paket Data, 3 => PLN, 4 => BPJS, 5 => Pasca Bayar, 6 => Telkom, 7=> Tagihan, 8 => Lainnya 1,  9 => Lainnya 2, 10 => lainnya 3');
            $table->string('invoice', 40);
            $table->decimal('total_price', 12, 2);
            $table->date('sale_date');
            $table->smallInteger('status')->default(0)->comment('0 = belum, 1 = konfirmasi member, 2 = konfirmasi admin, 3 = batal');
            $table->string('reason', 175)->nullable();
            $table->smallInteger('buy_metode')->default(0)->comment('2 = Transfer Bank, 3 = Tron');
            $table->string('tron', 200)->nullable();
            $table->string('tron_transfer', 200)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_no', 50)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->smallInteger('royalti_metode')->default(0)->comment('1 = Transfer Bank, 2 = Tron');
            $table->string('royalti_tron', 200)->nullable();
            $table->string('royalti_tron_transfer', 200)->nullable();
            $table->string('royalti_bank_name', 100)->nullable();
            $table->string('royalti_account_no', 50)->nullable();
            $table->string('royalti_account_name', 100)->nullable();
            $table->timestamp('royalti_metode_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            
            
            $table->index('user_id');
            $table->index('vendor_id');
            $table->index('type_pobx');
            $table->index('invoice');
            $table->index('total_price');
            $table->index('sale_date');
            $table->index('status');
            $table->index('buy_metode');
            $table->index('created_at');
            $table->index('deleted_at');
            $table->index('royalti_metode');
            $table->index('royalti_metode_at');
        });
    }

    public function down() {
        Schema::dropIfExists('x_master_sales');
    }
}
