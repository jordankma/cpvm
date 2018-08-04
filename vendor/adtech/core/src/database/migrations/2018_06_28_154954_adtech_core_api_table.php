<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdtechCoreApiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_core')->create('adtech_core_api', function (Blueprint $table) {
            $table->increments('api_id');
            $table->tinyInteger('package_id', false, true)->default(0);
            $table->string('name');
            $table->string('link');
            $table->text('description')->nullable();
            $table->text('datademo')->nullable();
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
        Schema::connection('mysql_core')->dropIfExists('adtech_core_api');
    }
}
