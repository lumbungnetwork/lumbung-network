<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueToBonusMember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bonus_member', function (Blueprint $table) {
            $table->unique(['user_id', 'from_user_id', 'type', 'bonus_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bonus_member', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'from_user_id', 'type', 'bonus_date']);
        });
    }
}
