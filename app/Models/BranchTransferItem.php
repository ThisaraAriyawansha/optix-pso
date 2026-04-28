<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BranchTransferItem extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = ['transfer_id', 'product_id', 'variant_id', 'qty_requested', 'qty_received'];

    public function transfer()
    {
        return $this->belongsTo(BranchTransfer::class, 'transfer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
