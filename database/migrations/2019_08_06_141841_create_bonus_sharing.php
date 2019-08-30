<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusSharing extends Migration {

    public function up() {
        Schema::create('bonus_sharing', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(1)->comment('0 = tidak aktif, 1 = aktif');
            $table->smallInteger('id_type')->default(0)->comment('2 => manager, 3 => SM, 4=> EM, 5 => SEM, 6 => GM');
            $table->integer('max_omzet')->default(0);
            $table->integer('persentase')->default(0);
            $table->integer('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('is_active');
            $table->index('id_type');
        });
    }

    public function down() {
        Schema::dropIfExists('bonus_sharing');
    }
}
