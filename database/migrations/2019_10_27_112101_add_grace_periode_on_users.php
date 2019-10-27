<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGracePeriodeOnUsers extends Migration {

    public function up() {
        Schema::table('users', function(Blueprint $table){
            $table->smallInteger('grace_period')->default(0)->comment('0 = member active, 1 = 1st grace periode 2= 2nd grace periode (blokir)');
            $table->timestamp('grace_period_at')->nullable();
            $table->smallInteger('pin_activate')->default(1);
            $table->timestamp('pin_activate_at')->nullable();
            
            $table->index('grace_period');
            $table->index('grace_period_at');
            $table->index('pin_activate');
            $table->index('pin_activate_at');
        });
    }

    public function down() {
        Schema::table('users', function(Blueprint $table){
            $table->dropColumn('grace_period');
            $table->dropColumn('grace_period_at');
            $table->dropColumn('pin_activate');
            $table->dropColumn('pin_activate_at');
        });
    }
}
