<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalBonaryOnBonusMember extends Migration {

    public function up() {
        Schema::table('bonus_member', function(Blueprint $table){
            $table->integer('total_binary')->nullable();
            $table->integer('total_activated')->nullable();
            $table->integer('total_all_binary')->nullable();
            $table->decimal('bonus_index', 12, 2)->nullable();
            $table->date('index_date')->nullable();
            $table->integer('bonus_setting')->nullable();
            
            $table->index('total_binary');
            $table->index('index_date');
        });
    }

    public function down() {
        Schema::table('bonus_member', function(Blueprint $table){
            $table->dropColumn('total_binary');
            $table->dropColumn('total_activated');
            $table->dropColumn('total_all_binary');
            $table->dropColumn('bonus_index');
            $table->dropColumn('index_date');
            $table->dropColumn('bonus_setting');
        });
    }
}
