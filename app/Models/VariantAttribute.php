<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class VariantAttribute extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = ['variant_id', 'attr_key', 'attr_value'];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
