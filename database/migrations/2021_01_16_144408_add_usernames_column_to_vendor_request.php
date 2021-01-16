<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsernamesColumnToVendorRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_request', function (Blueprint $table) {
            $table->string('usernames', 80)->nullable()->after('user_id');
            $table->string('delegate', 70)->nullable()->after('usernames');
            $table->string('hash', 70)->nullable()->after('delegate');
            $table->string('reason', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_request', function (Blueprint $table) {
            $table->dropColumn('usernames');
            $table->dropColumn('delegate');
            $table->dropColumn('hash');
            $table->dropColumn('reason');
        });
    }
}
