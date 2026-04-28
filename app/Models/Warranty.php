<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Warranty extends Model
{
    use HasUuids;

    protected $fillable = [
        'customer_id', 'invoice_item_id', 'product_id', 'serial_number',
        'warranty_type', 'start_date', 'end_date', 'duration_months', 'terms',
    ];

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date'];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoiceItem()
    {
        return $this->belongsTo(InvoiceItem::class, 'invoice_item_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function isExpired(): bool
    {
        return $this->end_date->isPast();
    }
}
