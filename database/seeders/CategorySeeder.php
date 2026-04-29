<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Laptops',
            'Desktops',
            'Monitors',
            'Keyboards & Mice',
            'Printers & Scanners',
            'Networking',
            'Storage Devices',
            'RAM & Memory',
            'Graphics Cards',
            'Processors',
            'Power Supplies',
            'Cables & Adapters',
            'Accessories',
            'Software',
            'Repair Parts',
        ];

        foreach ($categories as $name) {
            Category::create([
                'name'        => $name,
                'slug'        => Str::slug($name),
                'description' => null,
            ]);
        }
    }
}
