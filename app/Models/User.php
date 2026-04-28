<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'branch_id', 'name', 'email', 'phone', 'password', 'role', 'is_active', 'last_login',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login' => 'datetime',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'cashier_id');
    }

    public function repairJobs()
    {
        return $this->hasMany(RepairJob::class, 'technician_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    public function isTechnician(): bool
    {
        return $this->role === 'technician';
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        return strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
    }
}
