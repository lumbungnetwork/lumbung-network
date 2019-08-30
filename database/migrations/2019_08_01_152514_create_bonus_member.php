<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusMember extends Migration {
    
    public function up() {
        Schema::create('bonus_member', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('from_user_id')->nullable();
            $table->smallInteger('type')->default(1)->comment('1 => Bonus Sponsor, 2 => ...');
            $table->smallInteger('poin_type')->default(1)->comment('1 => Tipe Poin Bonus');
            $table->decimal('bonus_price', 12, 2);
            $table->date('bonus_date');
            $table->smallInteger('level_id')->nullable();
            $table->integer('total_pin')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            
            $table->index('user_id');
            $table->index('from_user_id');
            $table->index('type');
            $table->index('poin_type');
            $table->index('bonus_date');
            $table->index('level_id');
            $table->index('total_pin');
            $table->index('created_at');
        });
    }

    public function down() {
        Schema::dropIfExists('bonus_member');
    }
}
