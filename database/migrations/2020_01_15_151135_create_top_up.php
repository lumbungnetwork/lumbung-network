<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopUp extends Migration {

    public function up() {
        Schema::create('top_up', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->decimal('nominal', 12, 2);
            $table->integer('unique_digit');
            $table->smallInteger('status')->default(0)->comment('0 = member topup, 1 = member transfer 2 = tuntas dr admin, 3 = batal');
            $table->integer('bank_perusahaan_id')->nullable();
            $table->string('reason', 150)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('tuntas_at')->nullable();
            
            $table->index('user_id');
            $table->index('nominal');
            $table->index('status');
            $table->index('bank_perusahaan_id');
            $table->index('created_at');
            $table->index('deleted_at');
            $table->index('tuntas_at');
        });
    }

    public function down() {
        Schema::dropIfExists('top_up');
    }
}
