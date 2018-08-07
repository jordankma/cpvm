<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpvmClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_cpvm')->create('classes', function (Blueprint $table) {
            $table->increments('classes_id');
            $table->string('create_by')->comment('cua nguoi dang tin');
            $table->string('name');
            $table->string('alias');
            $table->integer('level_id', false, true);
            $table->integer('priority', false, true)->nullable();
            $table->string('type')->comment('class: lop thuong exam: lop luyen thi');
            $table->string('background_mobile');
            $table->string('color_mobile');

            $table->string('status')->comment('trang thai')->default('enable');

            $table->tinyInteger('kids_class', false, true)->comment('lớp mầm non; 0: là không phải lớp mầm non; 1:là lớp kỹ năng sống; 2 là lớp bé giỏi tiếng anh')->default(0);
            $table->string('folder_name')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('level_id')->references('level_id')->on('level')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_cpvm')->dropIfExists('classes');
    }
}
