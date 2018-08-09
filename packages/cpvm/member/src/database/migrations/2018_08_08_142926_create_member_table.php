<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member', function (Blueprint $table) {
            $table->increments('member_id');
            $table->string('token')->nullable();
            $table->string('ids_id')->nullable();
            $table->string('token_ids')->nullable();
            $table->string('user_name');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->string('remember_token');
            $table->string('level');
            $table->string('day');
            $table->string('month');
            $table->string('year');
            $table->datetime('birthday');
            $table->string('type')->default('user')->comment('user tk thuong, teacher tk giao vien, parent phu huynh');
            $table->string('avatar');
            $table->integer('active')->default(0);
            $table->integer('lock')->default(0);
            $table->datetime('lock_time')->nullable();
            $table->text('childs');
            $table->text('parent');
            $table->datetime('last_login')->nullable();
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member');
    }
}
