<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SerialNumber extends Model
{
    use HasUuids;

    protected $fillable = [
        'product_id', 'variant_id', 'branch_id', 'serial_number', 'status', 'purchased_at', 'notes',
    ];

    protected function casts(): array
    {
        return ['purchased_at' => 'date'];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
