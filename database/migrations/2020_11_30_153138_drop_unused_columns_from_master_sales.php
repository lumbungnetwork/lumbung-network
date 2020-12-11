<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUnusedColumnsFromMasterSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_sales', function (Blueprint $table) {
            $table->dropColumn(['is_stockist', 'royalti_metode', 'royalti_tron', 'royalti_tron_transfer', 'royalti_bank_name', 'royalti_account_no', 'royalti_account_name', 'royalti_metode_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_sales', function (Blueprint $table) {
            //
        });
    }
}
