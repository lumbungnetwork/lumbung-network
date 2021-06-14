<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBinaryIndexHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('binary_index_history', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date')->unique();
            $table->decimal('index', 7, 2);
            $table->integer('total_premiums');
            $table->integer('total_pairs');
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
        Schema::dropIfExists('binary_index_history');
    }
}
