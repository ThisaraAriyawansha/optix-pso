<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = ['role', 'feature', 'enabled'];

    protected $casts = ['enabled' => 'boolean'];

    // All features that can be toggled per role
    public static array $features = [
        'pos'            => 'New Sale',
        'quotations'     => 'Quotations',
        'invoices'       => 'Invoices',
        'products'       => 'Products',
        'categories'     => 'Categories',
        'suppliers'      => 'Suppliers',
        'stock'          => 'Stock',
        'purchase_orders'=> 'Purchase Orders',
        'transfers'      => 'Transfers',
        'customers'      => 'Customers',
        'installments'   => 'Installments',
        'loyalty'        => 'Loyalty Points',
        'coupons'        => 'Coupons',
        'repairs'        => 'Repair Jobs',
        'reports_sales'  => 'Sales Report',
        'reports_stock'  => 'Stock Report',
        'reports_expenses'=> 'Expense Report',
    ];

    // Returns ['feature' => bool] for a given role, with cache
    public static function forRole(string $role): array
    {
        if ($role === 'admin') {
            return array_fill_keys(array_keys(static::$features), true);
        }

        return cache()->remember("role_permissions_{$role}", 60, function () use ($role) {
            $rows = static::where('role', $role)->pluck('enabled', 'feature');
            $keys = array_keys(static::$features);
            return array_combine($keys, array_map(fn($f) => (bool) ($rows[$f] ?? false), $keys));
        });
    }

    public static function clearCache(string $role): void
    {
        cache()->forget("role_permissions_{$role}");
    }
}
