<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMessageOnPpob extends Migration {

    public function up() {
        Schema::table('ppob', function(Blueprint $table){
            $table->string('buyer_code', 50)->nullable();
            $table->string('product_name', 75)->nullable();
            $table->text('message')->nullable();
            
            $table->index('buyer_code');
        });
    }

    public function down() {
        Schema::table('ppob', function(Blueprint $table){
            $table->dropColumn('buyer_code');
            $table->dropColumn('product_name');
            $table->dropColumn('message');
        });
    }
}
