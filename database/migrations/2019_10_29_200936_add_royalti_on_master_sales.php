<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoyaltiOnMasterSales extends Migration {

    public function up() {
        Schema::table('master_sales', function(Blueprint $table){
            $table->smallInteger('royalti_metode')->default(0)->comment('1 = Transfer Bank, 2 = Tron');
            $table->string('royalti_tron', 200)->nullable();
            $table->string('royalti_tron_transfer', 200)->nullable();
            $table->string('royalti_bank_name', 100)->nullable();
            $table->string('royalti_account_no', 50)->nullable();
            $table->string('royalti_account_name', 100)->nullable();
            $table->timestamp('royalti_metode_at')->nullable();
            
            $table->index('royalti_metode');
            $table->index('royalti_metode_at');
        });
    }

    public function down() {
        Schema::table('master_sales', function(Blueprint $table){
            $table->dropColumn('royalti_metode');
            $table->dropColumn('royalti_tron');
            $table->dropColumn('royalti_tron_transfer');
            $table->dropColumn('royalti_bank_name');
            $table->dropColumn('royalti_account_no');
            $table->dropColumn('royalti_account_name');
            $table->dropColumn('royalti_metode_at');
        });
    }
}
