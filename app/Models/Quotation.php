<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Quotation extends Model
{
    use HasUuids;

    protected $fillable = [
        'customer_id', 'branch_id', 'created_by', 'quote_number', 'status', 'valid_until',
        'customer_name', 'customer_phone', 'customer_email', 'subtotal', 'discount_type',
        'discount_value', 'discount_amount', 'tax_amount', 'total', 'terms_and_conditions', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'valid_until' => 'date',
            'subtotal' => 'decimal:2',
            'discount_value' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class)->orderBy('sort_order');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function getCustomerDisplayNameAttribute(): string
    {
        return $this->customer?->name ?? $this->customer_name ?? 'Walk-in Customer';
    }
}
