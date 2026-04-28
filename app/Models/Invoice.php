<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Invoice extends Model
{
    use HasUuids;

    protected $fillable = [
        'quotation_id', 'customer_id', 'branch_id', 'cashier_id', 'invoice_number', 'type', 'status',
        'customer_name', 'customer_phone', 'subtotal', 'discount_type', 'discount_value',
        'discount_amount', 'tax_amount', 'total', 'amount_paid', 'amount_due', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_value' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'amount_due' => 'decimal:2',
        ];
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function warranties()
    {
        return $this->hasManyThrough(Warranty::class, InvoiceItem::class, 'invoice_id', 'invoice_item_id');
    }

    public function getCustomerDisplayNameAttribute(): string
    {
        return $this->customer?->name ?? $this->customer_name ?? 'Walk-in Customer';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'paid' => 'success',
            'partial' => 'warning',
            'cancelled', 'refunded' => 'danger',
            'issued' => 'info',
            default => 'gray',
        };
    }
}
