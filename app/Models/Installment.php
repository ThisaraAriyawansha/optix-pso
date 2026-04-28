<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Installment extends Model
{
    use HasUuids;

    protected $fillable = [
        'customer_id', 'invoice_id', 'total_amount', 'down_payment', 'amount_paid',
        'balance', 'installment_count', 'installment_amount', 'start_date', 'next_due_date', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'down_payment' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'balance' => 'decimal:2',
            'installment_amount' => 'decimal:2',
            'start_date' => 'date',
            'next_due_date' => 'date',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(InstallmentPayment::class);
    }

    public function isOverdue(): bool
    {
        return $this->next_due_date && $this->next_due_date->isPast() && $this->status === 'active';
    }
}
