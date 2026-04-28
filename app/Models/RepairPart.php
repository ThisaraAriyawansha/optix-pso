<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RepairPart extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = ['repair_job_id', 'product_id', 'description', 'qty', 'unit_cost', 'line_total'];

    protected function casts(): array
    {
        return ['unit_cost' => 'decimal:2', 'line_total' => 'decimal:2'];
    }

    public function repairJob()
    {
        return $this->belongsTo(RepairJob::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
