<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePengirimanPaket extends Migration {

    public function up() {
        Schema::create('pengiriman_paket', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('total_pin');
            $table->text('alamat_kirim');
            $table->smallInteger('status')->default(0)->comment('0 = belum, 1 = sudah dikirim, 2 = batal');
            $table->string('reason', 150)->nullable();
            $table->string('kurir_name', 70)->nullable();
            $table->string('no_resi', 30)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('kirim_at')->nullable();
            
            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
            $table->index('kirim_at');
            $table->index('kurir_name');
            $table->index('no_resi');
        });
    }

    public function down() {
        Schema::dropIfExists('pengiriman_paket');
    }
}
