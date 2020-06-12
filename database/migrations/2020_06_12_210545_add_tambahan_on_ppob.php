<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTambahanOnPpob extends Migration {

    public function up() {
        Schema::table('ppob', function(Blueprint $table){
            $table->decimal('harga_modal', 12, 2)->nullable();
        });
    }

    public function down() {
        Schema::table('ppob', function(Blueprint $table){
            $table->dropColumn('harga_modal');
        });
    }
}
