<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 100);
            $table->string('email', 100);
            $table->string('hp', 25)->nullable();
            $table->string('password', 100);
            $table->string('2fa', 100)->nullable();
            $table->string('username', 50)->nullable();
            $table->smallInteger('user_type')->default(10)->comment('1 = super admin, 2 = master admin, 3 = admin, 10 = member');
            $table->smallInteger('member_type')->default(0)->comment('9 = Starter, 10 = Premium');
            $table->integer('sponsor_id')->nullable();
            $table->smallInteger('total_sponsor')->default(0);
            $table->integer('upline_id')->nullable();
            $table->integer('kiri_id')->nullable();
            $table->integer('kanan_id')->nullable();
            $table->text('upline_detail')->nullable();
            $table->timestamp('placement_at')->nullable();
            $table->string('tron', 40)->nullable()->comment('User\'s TRON addresss');

            $table->string('full_name', 100)->nullable()->comment('Permanent, tied to bank account name');
            $table->string('alamat', 255)->nullable();
            $table->string('provinsi', 70)->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('kecamatan', 175)->nullable();
            $table->string('kelurahan', 175)->nullable();
            $table->tinyInteger('is_profile')->default(0);

            $table->timestamps();
            $table->rememberToken();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
