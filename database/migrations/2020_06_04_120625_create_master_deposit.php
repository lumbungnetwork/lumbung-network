<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterDeposit extends Migration {

    public function up() {
        Schema::create('master_deposit', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('total_deposit')->default(0);
            $table->smallInteger('type_deposit')->default(1)->comment('1 = deposit bertambah ke master, 2 = pemotongan deposit. 3 => deposit berkurang vendor tarik deposit');
            $table->string('code', 25)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_no', 50)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('type_deposit');
            $table->index('code');
            $table->index('created_at');
        });
    }

    public function down() {
        Schema::dropIfExists('master_deposit');
    }
}
