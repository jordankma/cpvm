<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdtechCoreRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_core')->create('adtech_core_roles', function (Blueprint $table) {
            $table->increments('role_id')->index();
            $table->string('name');
            $table->tinyInteger('permission_locked', false, true)->default(0);
            $table->tinyInteger('sort', false, true)->default(99);
            $table->tinyInteger('status', false, true)->default(1);

            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });

        DB::connection('mysql_core')->table('adtech_core_roles')->insert([
            'name' => 'Super Administrator',
            'permission_locked' => 1,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'sort' => 1,
            'status' => 1,
        ]);

        DB::connection('mysql_core')->table('adtech_core_roles')->insert([
            'name' => 'Administrator',
            'permission_locked' => 0,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'sort' => 2,
            'status' => 1,
        ]);

        DB::connection('mysql_core')->table('adtech_core_roles')->insert([
            'name' => 'User',
            'permission_locked' => 0,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'sort' => 3,
            'status' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_core')->dropIfExists('adtech_core_roles');
    }
}
