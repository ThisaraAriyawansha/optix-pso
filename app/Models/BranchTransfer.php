<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BranchTransfer extends Model
{
    use HasUuids;

    protected $fillable = [
        'from_branch_id', 'to_branch_id', 'requested_by', 'approved_by',
        'transfer_number', 'status', 'requested_date', 'received_date', 'notes',
    ];

    protected function casts(): array
    {
        return ['requested_date' => 'date', 'received_date' => 'date'];
    }

    public function fromBranch()
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    public function toBranch()
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items()
    {
        return $this->hasMany(BranchTransferItem::class, 'transfer_id');
    }
}
