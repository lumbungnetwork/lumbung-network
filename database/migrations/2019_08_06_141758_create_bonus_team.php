<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusTeam extends Migration {

    public function up() {
        Schema::create('bonus_team', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(1)->comment('0 = tidak aktif, 1 = aktif');
            $table->integer('team_price')->default(0);
            $table->integer('max_day')->default(0);
            $table->smallInteger('member_type')->default(0)->comment('1 =>  reseller, 2 => Agen, 3 => Stockist 4 => Master Stockist, 5 => Director Stockist');
            $table->integer('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('is_active');
            $table->index('member_type');
        });
    }

    public function down() {
        Schema::dropIfExists('bonus_team');
    }
}
