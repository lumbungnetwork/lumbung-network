<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsernamesColumnToStockistRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stockist_request', function (Blueprint $table) {
            $table->string('usernames')->nullable()->after('user_id');
            $table->string('delegate')->nullable()->after('usernames');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stockist_request', function (Blueprint $table) {
            $table->dropColumn('usernames');
            $table->dropColumn('delegate');
        });
    }
}
