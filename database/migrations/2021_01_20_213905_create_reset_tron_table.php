<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResetTronTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reset_tron', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('user_id');
            $table->string('username', 70);
            $table->string('delegate', 70);
            $table->string('voters', 120)->nullable();
            $table->string('old_address', 70);
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('reset_tron');
    }
}
