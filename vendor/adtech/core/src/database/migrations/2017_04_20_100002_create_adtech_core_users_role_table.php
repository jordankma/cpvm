<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdtechCoreUsersRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_core')->create('adtech_core_users_role', function (Blueprint $table) {
            $table->integer('user_id', false, true);
            $table->integer('role_id', false, true);

            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';

            $table->foreign('user_id')->references('user_id')->on('adtech_core_users')->onDelete('cascade');
            $table->foreign('role_id')->references('role_id')->on('adtech_core_roles')->onDelete('cascade');
        });

        DB::connection('mysql_core')->table('adtech_core_users_role')->insert([
            'user_id' => 1,
            'role_id' => 1,
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
        Schema::connection('mysql_core')->dropIfExists('adtech_core_users_role');
    }
}
