<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpvmLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_cpvm')->create('level', function (Blueprint $table) {
            $table->increments('level_id');
            $table->string('create_by')->comment('nguoi tao mon');
            $table->string('name');
            $table->string('alias');
            $table->string('color');
            $table->string('background');
            $table->string('color_mobile');
            $table->string('background_mobile');
            $table->tinyInteger('type')->default('1')->comment('1 mam non 2 cap thuong');

            $table->string('status')->comment('trang thai')->default('enable');

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
        Schema::connection('mysql_cpvm')->dropIfExists('level');
    }
}
