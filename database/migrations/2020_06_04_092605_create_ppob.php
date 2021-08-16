<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePpob extends Migration {

    public function up() {
        Schema::create('ppob', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vendor_id');
            $table->string('ppob_code', 25)->nullable();
            $table->smallInteger('type');
            $table->decimal('ppob_price', 12, 2);
            $table->date('ppob_date');
            $table->smallInteger('status')->default(0);
            $table->string('reason', 150)->nullable();
            $table->smallInteger('buy_metode')->default(0);
            $table->string('tron', 200)->nullable();
            $table->string('tron_transfer', 200)->nullable();
            $table->string('buyer_code', 50);
            $table->string('product_name', 75);
            $table->text('message')->nullable();
            $table->decimal('harga_modal', 12, 2);
            $table->timestamp('confirm_at')->nullable();
            $table->timestamps();
            $table->timestamp('tuntas_at')->nullable();
            $table->text('return_buy')->nullable();
            $table->text('vendor_cek')->nullable();
            $table->tinyInteger('vendor_approve')->default(0);
            $table->string('tx_id', 64)->nullable();
        });
    }

    public function down() {
        Schema::dropIfExists('ppob');
    }
}
