<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersProfile extends Migration{

    public function up() {
//        Schema::create('users_profile', function (Blueprint $table) {
//            $table->engine = 'InnoDB';
//            $table->increments('id');
//            $table->integer('user_id');
//            $table->string('full_name', 100)->nullable();
//            $table->string('alamat', 255)->nullable();
//            $table->string('provinsi', 70)->nullable();
//            $table->string('kota', 100)->nullable();
//            $table->string('kode_pos', 12)->nullable();
//            $table->smallInteger('gender')->nullable()->comment('1 = laki-laki, 2 = perempuan');
//            $table->string('ktp', 20)->nullable();
//            $table->timestamp('created_at')->useCurrent();
//            $table->timestamp('updated_at')->nullable();
//            
//            $table->index('user_id');
//            $table->index('full_name');
//            $table->index('provinsi');
//            $table->index('kota');
//            $table->index('gender');
//            $table->index('ktp');
//            $table->index('created_at');
//            $table->index('updated_at');
//        });
    }

    public function down() {
//        Schema::dropIfExists('users_profile');
    }
}
