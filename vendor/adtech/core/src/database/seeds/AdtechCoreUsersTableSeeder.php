<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class AdtechCoreUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::connection('mysql_core')->table('adtech_core_users')->insert([
            'email' => 'electric@gmail.com',
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
}
