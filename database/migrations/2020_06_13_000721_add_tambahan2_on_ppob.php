<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTambahan2OnPpob extends Migration {

    public function up() {
        Schema::table('ppob', function(Blueprint $table){
            $table->timestamp('confirm_at')->nullable();
            
            $table->index('confirm_at');
        });
    }

    public function down() {
        Schema::table('ppob', function(Blueprint $table){
            $table->dropColumn('confirm_at');
        });
    }
}
