<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackage extends Migration {

    public function up() {
        Schema::create('package', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('short_desc', 150);
            $table->smallInteger('type');
            $table->integer('pin');
            $table->decimal('stock_wd', 12, 2);
            $table->integer('discount');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            
            $table->index('name');
            $table->index('type');
            $table->index('pin');
            $table->index('created_at');
            $table->index('deleted_at');
        });
    }

    public function down() {
        Schema::dropIfExists('package');
    }
}
