<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdtechCoreLocalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_core')->create('adtech_core_locales', function (Blueprint $table) {
            $table->increments('locale_id')->index();
            $table->string('name');
            $table->string('alias')->unique();
            $table->string('icon')->nullable();
            $table->integer('domain_id', false, true)->index();
            $table->tinyInteger('status', false, true)->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });

        DB::connection('mysql_core')->table('adtech_core_locales')->insert([
            'name' => 'Tiếng việt',
            'alias' => 'vi',
            'icon' => '',
            'status' => 1,
            'domain_id' => 0,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        DB::connection('mysql_core')->table('adtech_core_locales')->insert([
            'name' => 'Tiếng Anh',
            'alias' => 'en',
            'icon' => '',
            'status' => 1,
            'domain_id' => 0,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_core')->dropIfExists('adtech_core_locales');
    }
}
