<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberPackage extends Migration {

    public function up() {
        Schema::create('member_package', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('request_user_id');
            $table->smallInteger('package_id');
            $table->string('name', 150);
            $table->string('short_desc', 150);
            $table->integer('total_pin');
            $table->smallInteger('status')->default(0)->comment('0 = belum, 1 = sudah');
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('user_id');
            $table->index('request_user_id');
            $table->index('package_id');
            $table->index('status');
            $table->index('total_pin');
            $table->index('created_at');
        });
    }

    public function down() {
        Schema::dropIfExists('member_package');
    }
}
