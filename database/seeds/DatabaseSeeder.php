<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "Super Admin",
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('superAdmin@123'),
            'phone_number' => '1234567890',
            'is_admin' => 'yes',
            'group1' => 1,
            'group2' => 1,
            'group3' => 1,
            'group4' => 1,
        ]);
    }
}