<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMetodePembayaranOnMasterItem extends Migration {

    public function up() {
        Schema::table('item_purchase_master', function(Blueprint $table){
            $table->smallInteger('buy_metode')->default(0)->comment('2 = Transfer Bank, 3 = Tron');
            $table->string('tron', 200)->nullable();
            $table->string('tron_transfer', 200)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_no', 50)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->timestamp('metode_at')->nullable();
            
            $table->index('buy_metode');
            $table->index('metode_at');
        });
    }

    public function down() {
        Schema::table('item_purchase_master', function(Blueprint $table){
            $table->dropColumn('buy_metode');
            $table->dropColumn('tron');
            $table->dropColumn('tron_transfer');
            $table->dropColumn('bank_name');
            $table->dropColumn('account_no');
            $table->dropColumn('account_name');
            $table->dropColumn('metode_at');
        });
    }
}
