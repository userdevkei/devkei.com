<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password) {
            $this->command?->error('Set ADMIN_EMAIL and ADMIN_PASSWORD in .env first.');
            return;
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->forceFill([
            'name' => env('ADMIN_NAME', 'DevKei Admin'),
            'password' => Hash::make($password),
            'is_admin' => true,
        ])->save();

        $this->command?->info("Admin ready: {$email}");
    }
}
