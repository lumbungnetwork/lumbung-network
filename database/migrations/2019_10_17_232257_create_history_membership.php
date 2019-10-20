<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryMembership extends Migration {

    public function up() {
        Schema::create('history_membership', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->smallInteger('member_type_old')->default(0);
            $table->smallInteger('member_type_new')->default(0);
            $table->smallInteger('type')->default(0)->comment('1 = upgrade member_type, 2 = downgrade member_type');
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('user_id');
            $table->index('member_type_old');
            $table->index('member_type_new');
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down() {
        Schema::dropIfExists('history_membership');
    }
}
