<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTronPerusahaan extends Migration {

    public function up() {
        Schema::create('tron', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->string('tron_name', 100);
            $table->string('tron', 200);
            $table->smallInteger('tron_type')->default(10)->comment('1 = perusahaan, 10 = member');
            $table->tinyInteger('is_active')->default(1)->comment('0 = tidak aktif, 1 = aktif');
            $table->timestamp('active_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('user_id');
            $table->index('tron_name');
            $table->index('tron');
            $table->index('tron_type');
            $table->index('is_active');
            $table->index('active_at');
            $table->index('created_at');
            $table->index('deleted_at');
        });
    }

    public function down() {
        Schema::dropIfExists('tron');
    }
}
