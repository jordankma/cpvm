<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdtechCoreUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_core')->create('adtech_core_users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('contact_name', 255);
            $table->string('salt', 3)->default('sal');
            $table->tinyInteger('role_id', false, true)->default(0);
            $table->tinyInteger('status', false, true)->default(1);
            $table->tinyInteger('activated', false, true)->default(0);
            $table->tinyInteger('permission_locked', false, true)->default(0);
            $table->rememberToken();

            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });

        DB::connection('mysql_core')->table('adtech_core_users')->insert([
            'email' => 'diennh@vnedutech.com',
            'password' => Hash::make('123456'),
            'contact_name' => 'Electric',
            'salt' => 'sal',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'activated' => 1,
            'status' => 1,
            'permission_locked' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_core')->dropIfExists('adtech_core_users');
    }
}
