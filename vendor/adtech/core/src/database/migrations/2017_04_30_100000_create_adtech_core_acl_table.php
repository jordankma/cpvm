<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdtechCoreAclTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_core')->create('adtech_core_acl', function (Blueprint $table) {
            $table->increments('acl_id');
            $table->integer('object_id', false, true);
            $table->enum('object_type', ['role', 'user', 'group']);
            $table->integer('domain_id', false, true)->default(1);
            $table->integer('allow')->nullable();
            $table->string('route_name')->nullable();
            $table->integer('route_name_crc', false, true)->nullable();
            $table->integer('created_user_id', false, true);
            $table->string('vendor', 255)->nullable();
            $table->string('package', 255)->nullable();
            $table->text('params')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });

        DB::connection('mysql_core')->table('adtech_core_acl')->insert([
            'object_id' => 1,
            'object_type' => 'role',
            'created_user_id' => 1,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'allow' => null,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_core')->dropIfExists('adtech_core_acl');
    }
}
