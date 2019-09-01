<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BonusTransfer extends Migration {

    public function up() {
        Schema::create('transfer_wd', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('user_bank');
            $table->string('wd_code', 25)->nullable();
            $table->smallInteger('type')->default(1)->comment('1 => Bonus Start, 2 => Bonus Team, ...');
            $table->decimal('wd_total', 12, 2);
            $table->date('wd_date');
            $table->integer('admin_fee');
            $table->smallInteger('status')->default(0)->comment('0 = belum, 1 = tuntas dr admin, 2 = batal');
            $table->string('reason', 150)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('transfer_at')->nullable();
            $table->timestamp('tuntas_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('user_id');
            $table->index('user_bank');
            $table->index('wd_code');
            $table->index('type');
            $table->index('wd_date');
            $table->index('status');
            $table->index('created_at');
            $table->index('transfer_at');
            $table->index('tuntas_at');
            $table->index('deleted_at');
        });
    }

    public function down() {
        Schema::dropIfExists('transfer_wd');
    }
}
