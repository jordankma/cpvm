<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassHasSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_has_subject', function (Blueprint $table) {
            $table->increments('class_has_subject_id');
            $table->integer('classes_id', false, true);
            $table->integer('subject_id', false, true);

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('classes_id')->references('classes_id')->on('classes')->onDelete('cascade');
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
        Schema::dropIfExists('class_has_subject');
    }
}
