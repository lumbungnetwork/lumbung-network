<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemPurchase extends Migration {

    public function up() {
        Schema::create('item_purchase', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('purchase_id')->unsigned();
            $table->integer('master_item_id')->unsigned();
            $table->smallInteger('stockist_id');
            $table->decimal('qty', 8, 2);
            $table->decimal('sisa', 8, 2);
            $table->decimal('price', 12, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('purchase_id');
            $table->index('master_item_id');
            $table->index('stockist_id');
            $table->index('qty');
            $table->index('sisa');
            $table->index('price');
            $table->index('created_at');
            $table->index('deleted_at');
        });
    }

    public function down() {
        Schema::dropIfExists('item_purchase');
    }
}
