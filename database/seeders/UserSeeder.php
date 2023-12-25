<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::truncate();

        User::create(
            [
                'name' => 'Super Admin',
                'username' => 'super_admin',
                'email' => 'super.admin@gmail.com',
                'phone' => '0123456789',
                'password' => bcrypt('12345678'),
                'status' => 1,
                'role' => 'super_admin',
                'status' => 1,
                'remember_token' => Str::random(10),
            ]
        );

        User::create(
            [
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('12345678'),
                'phone' => '0987654321',
                'role' => 'admin',
                'status' => 1,
                'remember_token' => Str::random(10),
            ]
        );
    }
}
