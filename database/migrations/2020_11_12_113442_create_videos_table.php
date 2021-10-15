<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('video_id');
//            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('category_id');
            $table->string('poster');
            $table->boolean('poster_set')->default(false);
            $table->integer('position')->default(0);
            $table->timestamps();

//            $table->foreign('package_id')->references('id')->on('packages')->onDelete('restrict');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
