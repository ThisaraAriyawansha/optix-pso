<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasUuids;

    protected $fillable = [
        'category_id', 'supplier_id', 'name', 'description', 'sku', 'barcode',
        'brand', 'model', 'cost_price', 'selling_price', 'tax_rate',
        'has_variants', 'track_serial', 'track_stock', 'reorder_point', 'image_url', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'has_variants' => 'boolean',
            'track_serial' => 'boolean',
            'track_stock' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function serialNumbers()
    {
        return $this->hasMany(SerialNumber::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getStockForBranch(string $branchId): int
    {
        return $this->stock()->where('branch_id', $branchId)->sum('qty');
    }
}
