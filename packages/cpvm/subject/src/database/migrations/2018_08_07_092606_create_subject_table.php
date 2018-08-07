<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_cpvm')->create('subject', function (Blueprint $table) {
            $table->increments('subject_id');
            $table->string('create_by')->comment('nguoi tao mon');
            $table->string('name');
            $table->string('alias');
            $table->integer('class_id', false, true)->nullable();
            $table->integer('priority', false, true)->nullable();

            $table->string('background_color');
            $table->string('icon');
            $table->string('background_color_mobile');
            $table->string('icon_mobile');

            $table->string('status')->comment('trang thai')->default('enable');
            $table->string('folder_name')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_cpvm')->dropIfExists('subject');
    }
}
