<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResubscribeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resubscribe', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('user_id');
            $table->string('hash', 64);
            $table->unique(['user_id', 'hash']);
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
        Schema::dropIfExists('resubscribe');
    }
}
