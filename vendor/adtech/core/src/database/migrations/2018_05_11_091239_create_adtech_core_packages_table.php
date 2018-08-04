<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdtechCorePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::connection('mysql_core')->create('adtech_core_packages', function (Blueprint $table) {
            $table->increments('package_id')->index();
            $table->string('space');
            $table->string('package');
            $table->string('package_alias');
            $table->string('module');
            $table->string('module_alias');
            $table->integer('create_by', false, true)->default(0);
            $table->tinyInteger('status', false, true)->default(0);

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
        Schema::connection('mysql_core')->dropIfExists('adtech_core_packages');
    }
}
