<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVStock extends Migration {

    public function up() {
        Schema::create('vstock', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('purchase_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->smallInteger('type')->comment('1 = purchase bertambah, 2 = purchase berkurang');
            $table->decimal('amount', 8, 2);
            $table->integer('vsales_id')->nullable();
            $table->integer('vendor_id')->nullable()->unsigned();
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('purchase_id');
            $table->index('user_id');
            $table->index('type');
            $table->index('amount');
            $table->index('vsales_id');
            $table->index('vendor_id');
            $table->index('created_at');
        });
    }

    public function down() {
        Schema::dropIfExists('vstock');
    }
}
