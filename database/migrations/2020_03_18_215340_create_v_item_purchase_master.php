<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVItemPurchaseMaster extends Migration {

    public function up() {
        Schema::create('vitem_purchase_master', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->smallInteger('vendor_id');
            $table->decimal('price', 12, 2);
            $table->smallInteger('status')->default(0)->comment('0 = belum, 1 = sudah dikirim, 2 = batal');
            $table->smallInteger('buy_metode')->default(0)->comment('2 = Transfer Bank, 3 = Tron');
            $table->string('tron', 200)->nullable();
            $table->string('tron_transfer', 200)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_no', 50)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->timestamp('metode_at')->nullable();
            $table->integer('submit_by')->default(1);
            $table->timestamp('submit_at')->nullable();
            $table->string('reason', 200)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('vendor_id');
            $table->index('status');
            $table->index('buy_metode');
            $table->index('metode_at');
            $table->index('submit_by');
            $table->index('submit_at');
            $table->index('created_at');
        });
    }

    public function down() {
        Schema::dropIfExists('vitem_purchase_master');
    }
}
