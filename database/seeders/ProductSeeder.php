<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::first();
        $laptopCat = Category::where('name', 'Laptops')->first();
        $accessoryCat = Category::where('name', 'Accessories')->first();
        $ramCat = Category::where('name', 'RAM & Memory')->first();

        $products = [
            [
                'name'          => 'HP Pavilion 15',
                'sku'           => 'HP-PAV-15',
                'category_id'   => $laptopCat?->id,
                'description'   => 'HP Pavilion 15 inch laptop, Core i5, 8GB RAM, 512GB SSD',
                'cost_price'    => 75000.00,
                'selling_price' => 89900.00,
                'reorder_point' => 2,
                'is_active'     => true,
                'track_stock'   => true,
            ],
            [
                'name'          => 'Dell Inspiron 14',
                'sku'           => 'DELL-INS-14',
                'category_id'   => $laptopCat?->id,
                'description'   => 'Dell Inspiron 14 inch, Core i7, 16GB RAM, 1TB SSD',
                'cost_price'    => 110000.00,
                'selling_price' => 132000.00,
                'reorder_point' => 2,
                'is_active'     => true,
                'track_stock'   => true,
            ],
            [
                'name'          => 'Kingston 8GB DDR4 RAM',
                'sku'           => 'KNG-8G-DDR4',
                'category_id'   => $ramCat?->id,
                'description'   => 'Kingston 8GB DDR4 2666MHz desktop memory',
                'cost_price'    => 3500.00,
                'selling_price' => 4500.00,
                'reorder_point' => 5,
                'is_active'     => true,
                'track_stock'   => true,
            ],
            [
                'name'          => 'Logitech MK270 Wireless Combo',
                'sku'           => 'LOGI-MK270',
                'category_id'   => $accessoryCat?->id,
                'description'   => 'Wireless keyboard and mouse combo',
                'cost_price'    => 3200.00,
                'selling_price' => 4200.00,
                'reorder_point' => 5,
                'is_active'     => true,
                'track_stock'   => true,
            ],
            [
                'name'          => 'HDMI Cable 1.8m',
                'sku'           => 'HDMI-1.8',
                'category_id'   => $accessoryCat?->id,
                'description'   => 'High speed HDMI cable, 1.8 meter',
                'cost_price'    => 350.00,
                'selling_price' => 650.00,
                'reorder_point' => 10,
                'is_active'     => true,
                'track_stock'   => true,
            ],
        ];

        foreach ($products as $data) {
            $product = Product::create($data);

            if ($branch && $product->track_stock) {
                Stock::create([
                    'branch_id'    => $branch->id,
                    'product_id'   => $product->id,
                    'qty_on_hand'  => rand(5, 20),
                    'qty_reserved' => 0,
                ]);
            }
        }
    }
}
