<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKbbBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kbb_bonuses', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->tinyInteger('affiliate');
            $table->tinyInteger('type');
            $table->smallInteger('amount');
            $table->tinyInteger('forwarded')->default(0);
            $table->string('hash', 64);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kbb_bonuses');
    }
}
