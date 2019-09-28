<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlacementAtOnUsers extends Migration {

    public function up() {
        Schema::table('users', function(Blueprint $table){
            $table->timestamp('placement_at')->nullable();
            $table->index('placement_at');
        });
    }

    public function down() {
        Schema::table('users', function(Blueprint $table){
            $table->dropColumn('placement_at');
        });
    }
}
