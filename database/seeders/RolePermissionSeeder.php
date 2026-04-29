<?php

namespace Database\Seeders;

use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'cashier' => [
                'pos'             => true,
                'quotations'      => true,
                'invoices'        => true,
                'products'        => false,
                'categories'      => false,
                'suppliers'       => false,
                'stock'           => false,
                'purchase_orders' => false,
                'transfers'       => false,
                'customers'       => true,
                'installments'    => true,
                'loyalty'         => true,
                'coupons'         => true,
                'repairs'         => false,
                'reports_sales'   => false,
                'reports_stock'   => false,
                'reports_expenses'=> false,
            ],
            'technician' => [
                'pos'             => false,
                'quotations'      => false,
                'invoices'        => false,
                'products'        => false,
                'categories'      => false,
                'suppliers'       => false,
                'stock'           => false,
                'purchase_orders' => false,
                'transfers'       => false,
                'customers'       => true,
                'installments'    => false,
                'loyalty'         => false,
                'coupons'         => false,
                'repairs'         => true,
                'reports_sales'   => false,
                'reports_stock'   => false,
                'reports_expenses'=> false,
            ],
        ];

        foreach ($defaults as $role => $features) {
            foreach ($features as $feature => $enabled) {
                RolePermission::updateOrCreate(
                    ['role' => $role, 'feature' => $feature],
                    ['enabled' => $enabled]
                );
            }
        }
    }
}
