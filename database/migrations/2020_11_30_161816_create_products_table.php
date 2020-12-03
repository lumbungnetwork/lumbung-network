<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('type')->comment('1 = Stockist, 2 = Vendor');
            $table->integer('seller_id');
            $table->string('name', 65);
            $table->string('size', 15);
            $table->decimal('price', 12, 2);
            $table->string('desc', 140)->nullable();
            $table->smallInteger('qty')->default('0');
            $table->tinyInteger('category_id');
            $table->string('image', 100);
            $table->tinyInteger('is_active')->default('1');
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
}
