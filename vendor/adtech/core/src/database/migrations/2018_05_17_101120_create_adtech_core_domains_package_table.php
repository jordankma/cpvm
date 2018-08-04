<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdtechCoreDomainsPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_core')->create('adtech_core_domains_package', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('domain_id', false, true)->index();
            $table->integer('package_id', false, true)->index();
            $table->integer('status')->default(0);

            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';

            $table->foreign('domain_id')->references('domain_id')->on('adtech_core_domains')->onDelete('cascade');
            $table->foreign('package_id')->references('package_id')->on('adtech_core_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_core')->dropIfExists('adtech_core_domains_package');
    }
}
