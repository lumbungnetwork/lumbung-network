<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePpob extends Migration {

    public function up() {
        Schema::create('ppob', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('vendor_id')->unsigned();
            $table->string('ppob_code', 25)->nullable();
            $table->smallInteger('type')->comment('1 => Pulsa, 2 => Paket Data, 3=> PLN, 4 => BPJS, 5 => Pasca, 6 => Telkom, 7 => Tagihan, 8 => Lain ke-1, 9=> Lain  ke-2');
            $table->decimal('ppob_price', 12, 2);
            $table->date('ppob_date');
            $table->smallInteger('status')->default(0)->comment('0 = belum, 1 = member transfer 2 = tuntas dr vendor, 3 = batal');
            $table->string('reason', 150)->nullable();
            $table->smallInteger('buy_metode')->default(0)->comment('1 = COD, 2 = Transfer Bank, 3 = Tron');
            $table->string('tron', 200)->nullable();
            $table->string('tron_transfer', 200)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_no', 50)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('tuntas_at')->nullable();
            
            $table->index('user_id');
            $table->index('vendor_id');
            $table->index('ppob_code');
            $table->index('type');
            $table->index('status');
            $table->index('buy_metode');
            $table->index('created_at');
            $table->index('deleted_at');
            $table->index('tuntas_at');
        });
    }

    public function down() {
        Schema::dropIfExists('ppob');
    }
}
