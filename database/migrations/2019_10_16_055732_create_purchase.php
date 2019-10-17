<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchase extends Migration {

    public function up() {
        Schema::create('purchase', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 150);
            $table->string('code', 40);
            $table->string('image', 200);
            $table->string('ukuran', 100);
            $table->decimal('member_price', 12, 2);
            $table->decimal('stockist_price', 12, 2);
            $table->decimal('qty', 8, 2);
            $table->smallInteger('provinsi')->default(0)->unsigned();
            $table->smallInteger('kota')->default(0)->unsigned();
            $table->smallInteger('kecamatan')->default(0)->unsigned();
            $table->smallInteger('kelurahan')->default(0)->unsigned();
            $table->text('area')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('name');
            $table->index('code');
            $table->index('provinsi');
            $table->index('kota');
            $table->index('kecamatan');
            $table->index('kelurahan');
            $table->index('created_at');
            $table->index('deleted_at');
            
        });
    }

    public function down() {
        Schema::dropIfExists('purchase');
    }
}
