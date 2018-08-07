<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockHasClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_has_class', function (Blueprint $table) {
            $table->increments('block_has_class_id');
            $table->integer('block_id', false, true);
            $table->integer('classes_id', false, true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('block_id')->references('block_id')->on('block')->onDelete('cascade');
            $table->foreign('classes_id')->references('classes_id')->on('classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('block_has_class');
    }
}
