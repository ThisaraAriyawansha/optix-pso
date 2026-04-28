<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PurchaseOrderItem extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'po_id', 'product_id', 'variant_id', 'qty_ordered', 'qty_received', 'unit_cost', 'line_total',
    ];

    protected function casts(): array
    {
        return [
            'unit_cost' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
