<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreateByOnItemPurchaseMaster extends Migration {

    public function up() {
        Schema::table('item_purchase_master', function(Blueprint $table){
            $table->integer('submit_by')->default(1);
            $table->timestamp('submit_at')->nullable();
            $table->string('reason', 200)->nullable();
            
            $table->index('submit_by');
            $table->index('submit_at');
        });
    }

    public function down() {
        Schema::table('item_purchase_master', function(Blueprint $table){
            $table->dropColumn('submit_by');
            $table->dropColumn('submit_at');
            $table->dropColumn('reason');
        });
    }
}
