<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApproveAtOnMasterDEposit extends Migration {

    public function up() {
        Schema::table('master_deposit', function(Blueprint $table){
            $table->timestamp('submit_at')->nullable();
            $table->index('submit_at');
        });
    }

    public function down() {
        Schema::table('master_deposit', function(Blueprint $table){
            $table->dropColumn('submit_at');
        });
    }
}
