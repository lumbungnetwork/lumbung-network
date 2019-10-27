<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryGracePeriod extends Migration {

    public function up() {
        Schema::create('history_grace_period', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->smallInteger('period_old')->default(0);
            $table->smallInteger('period_new')->default(0);
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('user_id');
            $table->index('period_old');
            $table->index('period_new');
        });
    }

    public function down() {
        Schema::dropIfExists('history_grace_period');
    }
}
