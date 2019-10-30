<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemPurchaseMaster extends Migration {

    public function up() {
        Schema::create('item_purchase_master', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->smallInteger('stockist_id');
            $table->decimal('price', 12, 2);
            $table->smallInteger('status')->default(0)->comment('0 = belum, 1 = sudah dikirim, 2 = batal');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('stockist_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down() {
        Schema::dropIfExists('item_purchase_master');
    }
}
