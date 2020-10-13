<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "superAdmin",
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('superAdmin@123'),
            'phone_number' => '1234567890',
            'group1' => 1,
            'group2' => 1,
            'group3' => 1,
            'group4' => 1,
            'is_admin' => 'YES',
            'can_make_call' => 'NO',
            'portal_access' => 'YES',
            'designation' => 'superAdmin'

        ]);
    }
}
