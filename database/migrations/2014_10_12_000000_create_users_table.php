<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 100);
            $table->string('email', 100);
            $table->string('hp', 25)->nullable();
            $table->string('password', 100);
            $table->string('username', 50)->nullable();
            $table->tinyInteger('is_login')->default(1)->comment('0 = tidak aktif, 1 = aktif');
            $table->tinyInteger('is_active')->default(0)->comment('0 = tidak aktif, 1 = aktif');
            $table->smallInteger('user_type')->default(10)->comment('1 = super admin, 2 = master admin, 3 = admin, 10 = member');
            $table->smallInteger('id_type')->default(1)->comment('Berhubungan dgn type manager. 1=> member biasa, 11 => TL, 12 => Asmen, 13 => M, 14 => SM, 15 => EM, 16 => SEM, 17 => GM');
            $table->smallInteger('package_id')->nullable()->comment('jenis paket yg dibeli');
            $table->smallInteger('member_type')->default(0)->comment('Berhubungan dgn order paket diawal setelah diaktifasi. 0 => belum pernah aktifasi pin, 1 =>  reseller, 2 => Agen, 3 => Stockist 4 => Master Stockist');
            $table->smallInteger('member_status')->default(0)->comment('Berhubungan dgn pembelian total pin. 0 => belum pernah beli pin, 1 =>  member biasa (pebelian pin 1-99)  2=> Director Stockist (Pebelian pin >= 100 pin) ');
            $table->integer('sponsor_id')->nullable();
            $table->smallInteger('total_sponsor')->default(0);
            $table->integer('upline_id')->nullable();
            $table->integer('kiri_id')->nullable();
            $table->integer('kanan_id')->nullable();
            $table->smallInteger('total_kiri')->default(0);
            $table->smallInteger('total_kanan')->default(0);
            $table->text('upline_detail')->nullable();
            $table->tinyInteger('is_referal_link')->default(0)->comment('0 = bukan, 1 = iya');

            $table->string('full_name', 100)->nullable()->comment('buat di account_name bank');
            $table->string('alamat', 255)->nullable();
            $table->string('provinsi', 70)->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('kode_pos', 12)->nullable();
            $table->smallInteger('gender')->nullable()->comment('1 = laki-laki, 2 = perempuan');
            $table->string('ktp', 20)->nullable();
            $table->tinyInteger('is_profile')->default(0)->comment('0 = belum, 1 = sudah');

            $table->timestamp('active_at')->nullable();
            $table->timestamp('package_id_at')->nullable();
            $table->timestamp('upgrade_at')->nullable();
            $table->timestamp('member_status_at')->nullable();
            $table->timestamp('profile_created_at')->nullable();
            $table->timestamp('profile_updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->rememberToken();

            $table->index('name');
            $table->index('email');
            $table->index('password');
            $table->index('hp');
            $table->index('username');
            $table->index('is_login');
            $table->index('is_active');
            $table->index('user_type');
            $table->index('id_type');
            $table->index('package_id');
            $table->index('member_type');
            $table->index('member_status');
            $table->index('sponsor_id');
            $table->index('total_sponsor');
            $table->index('upline_id');
            $table->index('kiri_id');
            $table->index('kanan_id');
            $table->index('total_kiri');
            $table->index('total_kanan');
            $table->index('is_referal_link');

            $table->index('full_name');
            $table->index('provinsi');
            $table->index('kota');
            $table->index('gender');
            $table->index('ktp');
            $table->index('is_profile');

            $table->index('active_at');
            $table->index('package_id_at');
            $table->index('upgrade_at');
            $table->index('member_status_at');
            $table->index('profile_created_at');
            $table->index('profile_updated_at');
            $table->index('created_at');
            $table->index('deleted_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
