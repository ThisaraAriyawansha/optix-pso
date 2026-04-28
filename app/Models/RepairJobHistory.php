<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RepairJobHistory extends Model
{
    use HasUuids;

    protected $table = 'repair_job_history';

    protected $fillable = ['repair_job_id', 'user_id', 'from_status', 'to_status', 'notes'];

    public function repairJob()
    {
        return $this->belongsTo(RepairJob::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
