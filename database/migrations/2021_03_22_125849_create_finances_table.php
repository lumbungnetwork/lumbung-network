<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->string('username', 32)->unique();
            $table->string('email', 100);
            $table->string('password', 125);
            $table->string('chat_id', 20)->nullable();
            $table->tinyInteger('is_active')->default(0);
            $table->timestamp('active_at')->nullable();
            $table->unsignedBigInteger('sponsor_id')->default(1);
            $table->string('tron', 34)->nullable();
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
        Schema::drop('finances');
    }
}
