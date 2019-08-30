<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusRepeatOrder extends Migration {

    public function up() {
        Schema::create('bonus_repeat_order', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('is_active')->default(1)->comment('0 = tidak aktif, 1 = aktif');
            $table->integer('ro_price')->default(0);
            $table->smallInteger('level')->default(0)->comment('1 => Level 1, 2 => Level 2, ...');
            $table->integer('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('is_active');
            $table->index('level');
        });
    }

    public function down() {
        Schema::dropIfExists('bonus_repeat_order');
    }
}
