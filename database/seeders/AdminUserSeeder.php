<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Demo account shown on the login page: username "admin",
     * password "smkn1bakam".
     *
     * Looks up the existing row manually (by trimmed/lowercased email OR
     * username) instead of relying on Eloquent's updateOrCreate(), which
     * was hitting a unique constraint violation — likely due to a data
     * quirk (whitespace/case) in the pre-existing row that made its exact
     * WHERE match fail to find the record before inserting.
     */
    public function run(): void
    {
        $user = User::whereRaw('LOWER(TRIM(email)) = ?', ['admin@smkn1bakam.sch.id'])
            ->orWhere('username', 'admin')
            ->first();

        if ($user) {
            $user->name = 'Administrator';
            $user->username = 'admin';
            $user->email = 'admin@smkn1bakam.sch.id';
            $user->password = Hash::make('smkn1bakam');
            $user->role = 'superadmin';
            $user->email_verified_at = now();
            $user->save();

            $this->command->info("Updated existing user (id={$user->id}) with username 'admin'.");
        } else {
            User::create([
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@smkn1bakam.sch.id',
                'password' => Hash::make('smkn1bakam'),
                'role' => 'superadmin',
                'email_verified_at' => now(),
            ]);

            $this->command->info("Created new admin user with username 'admin'.");
        }
    }
}
