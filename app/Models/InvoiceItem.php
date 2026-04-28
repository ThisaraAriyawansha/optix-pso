<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InvoiceItem extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'invoice_id', 'product_id', 'variant_id', 'description', 'qty',
        'unit_price', 'cost_price', 'discount_pct', 'line_total', 'serial_number', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'discount_pct' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function warranty()
    {
        return $this->hasOne(Warranty::class, 'invoice_item_id');
    }
}
