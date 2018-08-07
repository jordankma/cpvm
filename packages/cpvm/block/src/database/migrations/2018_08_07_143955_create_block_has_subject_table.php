<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockHasSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_has_subject', function (Blueprint $table) {
            $table->increments('block_has_subject_id');
            $table->integer('block_id', false, true);
            $table->integer('subject_id', false, true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('block_id')->references('block_id')->on('block')->onDelete('cascade');
            $table->foreign('subject_id')->references('subject_id')->on('subject')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('block_has_subject');
    }
}
