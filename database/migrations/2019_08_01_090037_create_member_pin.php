<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberPin extends Migration {

    public function up() {
        Schema::create('member_pin', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('setting_pin');
            $table->integer('total_pin')->default(0);
            $table->string('pin_code', 25)->nullable();
            $table->string('transaction_code', 25)->nullable();
            $table->tinyInteger('is_used')->default(0)->comment('0 = free, 1 = aktif');
            $table->timestamp('used_at')->nullable();
            $table->integer('used_user_id')->nullable();
            $table->smallInteger('pin_status')->default(0)->comment('0 = pin masuk, 1 => pin aktifasi (keluar), 2 => pin transfer (keluar)');
            $table->integer('transfer_user_id')->nullable();
            $table->integer('transfer_from_user_id')->nullable();
            $table->tinyInteger('is_upgrade')->default(0);
            $table->tinyInteger('is_ro')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('user_id');
            $table->index('setting_pin');
            $table->index('pin_code');
            $table->index('transaction_code');
            $table->index('is_used');
            $table->index('used_at');
            $table->index('used_user_id');
            $table->index('pin_status');
            $table->index('transfer_user_id');
            $table->index('is_upgrade');
            $table->index('is_ro');
            $table->index('created_at');
            $table->index('deleted_at');
        });
    }

    public function down() {
        Schema::dropIfExists('member_pin');
    }
}
