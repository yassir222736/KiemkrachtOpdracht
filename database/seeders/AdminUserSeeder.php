<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed een admin-account voor de beveiligde beheerpagina.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kiemkracht.be'],
            [
                'name' => 'Kiemkracht Admin',
                'email' => 'admin@kiemkracht.be',
                'password' => Hash::make('Admin@Kiemkracht2026!'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
