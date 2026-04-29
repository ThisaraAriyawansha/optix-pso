<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::first();

        User::create([
            'name'      => 'Admin User',
            'email'     => 'admin@optix.lk',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'branch_id' => $branch?->id,
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Cashier One',
            'email'     => 'cashier@optix.lk',
            'password'  => Hash::make('password'),
            'role'      => 'cashier',
            'branch_id' => $branch?->id,
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Tech Nuwan',
            'email'     => 'tech@optix.lk',
            'password'  => Hash::make('password'),
            'role'      => 'technician',
            'branch_id' => $branch?->id,
            'is_active' => true,
        ]);
    }
}
