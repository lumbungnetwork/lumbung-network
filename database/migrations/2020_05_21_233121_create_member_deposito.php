<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberDeposito extends Migration {

    public function up() {
        Schema::create('member_deposito', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('total_deposito')->default(0);
            $table->string('transaction_code', 25)->nullable();
            $table->smallInteger('deposito_status')->default(0)->comment('0 = deposito masuk, 1 => deposito keluar, 2 => deposito transfer (keluar)');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('user_id');
            $table->index('transaction_code');
            $table->index('deposito_status');
            $table->index('created_at');
            $table->index('deleted_at');
        });
    }

    public function down() {
        Schema::dropIfExists('member_deposito');
    }
}
