<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryIndex extends Migration {

    public function up() {
        Schema::create('history_index', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('total_binary');
            $table->integer('total_activated');
            $table->decimal('bonus_index', 12, 2);
            $table->date('index_date');
            $table->integer('type_setting');
            $table->integer('bonus_pasangan_setting');
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('total_binary');
            $table->index('total_activated');
            $table->index('index_date');
            $table->index('type_setting');
            $table->index('created_at');
        });
    }

    public function down() {
        Schema::dropIfExists('history_index');
    }
}
