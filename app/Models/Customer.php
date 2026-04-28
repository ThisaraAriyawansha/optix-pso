<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Customer extends Model
{
    use HasUuids;

    protected $fillable = [
        'name', 'phone', 'email', 'address', 'id_number',
        'loyalty_points', 'credit_limit', 'credit_balance', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'loyalty_points' => 'integer',
            'credit_limit' => 'decimal:2',
            'credit_balance' => 'decimal:2',
        ];
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function loyaltyTransactions()
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function repairJobs()
    {
        return $this->hasMany(RepairJob::class);
    }

    public function warranties()
    {
        return $this->hasMany(Warranty::class);
    }
}
