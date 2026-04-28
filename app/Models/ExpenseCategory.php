<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ExpenseCategory extends Model
{
    use HasUuids;

    protected $fillable = ['name'];

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category_id');
    }
}
