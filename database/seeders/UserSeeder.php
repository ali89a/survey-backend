<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'user-1',
                'email' => 'user@gmail.com',
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'user-2',
                'email' => 'user2@gmail.com',
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'user-3',
                'email' => 'user3@gmail.com',
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'user-4',
                'email' => 'user4@gmail.com',
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'user-5',
                'email' => 'user5@gmail.com',
                'password' => bcrypt('12345678'),
            ],


        ]);
    }
}
