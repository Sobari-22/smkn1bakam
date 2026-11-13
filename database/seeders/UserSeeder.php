<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@smkn1bakam.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'superadmin',
        ]);
    }
}
