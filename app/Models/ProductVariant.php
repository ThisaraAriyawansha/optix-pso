<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductVariant extends Model
{
    use HasUuids;

    protected $fillable = [
        'product_id', 'name', 'sku', 'barcode', 'cost_price', 'selling_price', 'image_url', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributes()
    {
        return $this->hasMany(VariantAttribute::class, 'variant_id');
    }

    public function stock()
    {
        return $this->hasMany(Stock::class, 'variant_id');
    }
}
