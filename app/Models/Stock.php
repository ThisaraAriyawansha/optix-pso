<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Stock extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $table = 'stock';

    protected $fillable = ['branch_id', 'product_id', 'variant_id', 'qty_on_hand', 'qty_reserved', 'min_qty'];

    protected function casts(): array
    {
        return ['qty_on_hand' => 'integer', 'qty_reserved' => 'integer', 'min_qty' => 'integer'];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function isLow(): bool
    {
        return $this->qty_on_hand <= $this->min_qty;
    }
}
