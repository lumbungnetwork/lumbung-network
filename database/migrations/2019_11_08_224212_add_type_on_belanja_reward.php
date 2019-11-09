<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeOnBelanjaReward extends Migration {

    public function up() {
        Schema::table('belanja_reward', function(Blueprint $table){
            $table->smallInteger('type')->default(1);
            $table->index('type');
        });
    }

    public function down() {
        Schema::table('belanja_reward', function(Blueprint $table){
            $table->dropColumn('type');
        });
    }
}
