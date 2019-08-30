<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBank extends Migration {

    public function up() {
        Schema::create('bank', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->string('bank_name', 100);
            $table->string('account_no', 50);
            $table->string('account_name', 100);
            $table->smallInteger('bank_type')->default(10)->comment('1 = perusahaan, 10 = member');
            $table->tinyInteger('is_active')->default(1)->comment('0 = tidak aktif, 1 = aktif');
            $table->timestamp('active_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('user_id');
            $table->index('bank_name');
            $table->index('account_no');
            $table->index('account_name');
            $table->index('bank_type');
            $table->index('is_active');
            $table->index('created_at');
            $table->index('deleted_at');
        });
    }

    public function down() {
        Schema::dropIfExists('bank');
    }
}
