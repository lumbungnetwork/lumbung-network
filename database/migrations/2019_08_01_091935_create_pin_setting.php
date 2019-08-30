<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePinSetting extends Migration {

    public function up() {
        Schema::create('pin_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price', 12, 2);
            $table->tinyInteger('is_active')->default(1)->comment('0 = tidak aktif, 1 = aktif');
            $table->integer('created_by');
            $table->timestamp('active_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('is_active');
            $table->index('created_at');
        });
    }

    public function down() {
        Schema::dropIfExists('pin_setting');
    }
}
