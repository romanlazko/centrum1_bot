<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperDuperAdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'SuperDuperAdmin',
            'email' => 'super-duper-admin@admin.com',
            'password' => Hash::make('admin'),
        ])
        ->roles()
        ->create([
            'name' => 'super-duper-admin',
        ])
        ->permissions()
        ->create([
            'name' => 'default',
            'comment' => 'Default permission',
        ]);
    }
}