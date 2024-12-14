<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Nada Admin',
            'email' => 'admin@alnadahospital.com',
            'password' => Hash::make('n3da!!2o25'),
            'email_verified_at' => now()
        ]);
    }
}
