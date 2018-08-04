<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class AdtechCoreRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

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
}
