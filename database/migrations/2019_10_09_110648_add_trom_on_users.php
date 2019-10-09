<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTromOnUsers extends Migration {

    public function up() {
        Schema::table('users', function(Blueprint $table){
            $table->tinyInteger('is_tron')->default(0)->comment('0 = belum, 1 = sudah');
            $table->string('tron', 200)->nullable();
            $table->timestamp('tron_at')->nullable();
            $table->index('is_tron');
            $table->index('tron');
            $table->index('tron_at');
        });
    }

    public function down() {
        Schema::table('users', function(Blueprint $table){
            $table->dropColumn('is_tron');
            $table->dropColumn('tron');
            $table->dropColumn('tron_at');
        });
    }
}
