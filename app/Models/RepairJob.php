<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RepairJob extends Model
{
    use HasUuids;

    protected $fillable = [
        'customer_id', 'branch_id', 'technician_id', 'created_by', 'job_number',
        'device_type', 'device_brand', 'device_model', 'serial_number',
        'reported_issue', 'diagnosis', 'priority', 'status', 'estimated_cost', 'final_cost',
        'invoice_id', 'received_date', 'promised_date', 'completed_date', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'estimated_cost' => 'decimal:2',
            'final_cost' => 'decimal:2',
            'received_date' => 'date',
            'promised_date' => 'date',
            'completed_date' => 'date',
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

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function history()
    {
        return $this->hasMany(RepairJobHistory::class)->latest();
    }

    public function parts()
    {
        return $this->hasMany(RepairPart::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'received' => 'info',
            'diagnosing' => 'warning',
            'waiting_parts' => 'warning',
            'in_repair' => 'primary',
            'ready' => 'success',
            'delivered' => 'gray',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'received' => 'Received',
            'diagnosing' => 'Diagnosing',
            'waiting_parts' => 'Waiting Parts',
            'in_repair' => 'In Repair',
            'ready' => 'Ready',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }
}
