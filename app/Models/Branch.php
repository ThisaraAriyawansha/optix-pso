<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Branch extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'address', 'phone', 'email', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function repairJobs()
    {
        return $this->hasMany(RepairJob::class);
    }
}
