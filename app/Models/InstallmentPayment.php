<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InstallmentPayment extends Model
{
    use HasUuids;

    protected $fillable = [
        'installment_id', 'cashier_id', 'amount', 'method', 'reference', 'paid_date', 'notes',
    ];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'paid_date' => 'date'];
    }

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}
