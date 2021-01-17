<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueToTransferWd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_wd', function (Blueprint $table) {
            $table->unique(['user_id', 'wd_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_wd', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'wd_code']);
        });
    }
}
