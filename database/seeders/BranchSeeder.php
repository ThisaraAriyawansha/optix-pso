<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        Branch::create([
            'name'      => 'Main Branch',
            'code'      => 'BR001',
            'address'   => 'No. 123, Main Street, Colombo 03',
            'phone'     => '011-2345678',
            'email'     => 'main@optix.lk',
            'is_active' => true,
        ]);
    }
}
