<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterPin extends Migration  {

    public function up() {
        Schema::create('master_pin', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('total_pin')->default(0);
            $table->smallInteger('type_pin')->default(1)->comment('1 = pin bertambah ke master, 2 = pin berkurang dari master');
            $table->string('transaction_code', 25)->nullable();
            $table->string('reason', 200)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('type_pin');
            $table->index('transaction_code');
            $table->index('created_at');
        });
    }

    public function down() {
        Schema::dropIfExists('master_pin');
    }
}
