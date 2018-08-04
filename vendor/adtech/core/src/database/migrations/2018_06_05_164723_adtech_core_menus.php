<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdtechCoreMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::connection('mysql_core')->create('adtech_core_menus', function (Blueprint $table) {
            $table->increments('menu_id')->index();
            $table->string('name')->nullable();
            $table->string('alias');
            $table->string('type')->default('0')->comment('0:backend, 1:frontend');
            $table->string('group')->nullable();
            $table->string('typeData')->nullable();
            $table->string('typeView')->nullable();
            $table->string('route_name')->default('');
            $table->string('route_params')->nullable();
            $table->string('icon')->default('question');
            $table->integer('domain_id', false, true)->index();
            $table->integer('parent', false, true)->default(0);
            $table->tinyInteger('sort', false, true)->default(99);

            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::connection('mysql_core')->drop('adtech_core_menus');
    }
}
