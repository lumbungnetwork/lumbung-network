<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNews extends Migration {

    public function up() {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('created_by');
            $table->string('title', 200);
            $table->text('full_desc');
            $table->string('image', 200);
            $table->tinyInteger('publish')->default(1)->comment('0 = tidak aktif, 1 = aktif');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            
            $table->index('publish');
            $table->index('created_at');
        });
    }

    public function down()  {
        Schema::dropIfExists('news');
    }
}
