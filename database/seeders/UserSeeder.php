<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\UserSistem;

class UserSeeder extends Seeder
{
    public function run()
    {
        UserSistem::insert([
            [
                'nama' => 'admin',
                'username' => 'adminmaster',
                'password' => Hash::make('master321'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'operator',
                'username' => 'operatormaster',
                'password' => Hash::make('operator321'),
                'role' => 'operator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

