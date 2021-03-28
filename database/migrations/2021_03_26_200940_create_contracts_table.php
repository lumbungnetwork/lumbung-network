<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('strategy');
            $table->tinyInteger('status')->default(0)->comment('0 = Inactive, 1 = Active, 2 = Ended');
            $table->decimal('principal', 10, 2);
            $table->decimal('compounded', 10, 2)->default(0);
            $table->tinyInteger('grade')->default(0)->comment('0 = Processing, 1 = C, 2 = B, 3 = A, 4 = S');
            $table->tinyInteger('collateralized')->default(0)->comment('0 = No, 1 = Yes');
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('next_yield_at')->nullable();
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
        Schema::dropIfExists('contracts');
    }
}
