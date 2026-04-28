<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;

class StockService
{
    public function adjust(
        string $branchId,
        string $productId,
        ?string $variantId,
        int $qtyChange,
        string $type,
        string $reference = '',
        string $notes = ''
    ): Stock {
        $stock = Stock::firstOrCreate(
            ['branch_id' => $branchId, 'product_id' => $productId, 'variant_id' => $variantId],
            ['qty' => 0, 'min_qty' => 5]
        );

        $qtyBefore = $stock->qty;
        $stock->qty += $qtyChange;
        $stock->save();

        StockMovement::create([
            'branch_id' => $branchId,
            'product_id' => $productId,
            'variant_id' => $variantId,
            'user_id' => Auth::id(),
            'type' => $type,
            'qty_before' => $qtyBefore,
            'qty_change' => $qtyChange,
            'qty_after' => $stock->qty,
            'reference' => $reference,
            'notes' => $notes,
        ]);

        return $stock;
    }

    public function getStock(string $branchId, string $productId, ?string $variantId = null): int
    {
        return Stock::where('branch_id', $branchId)
            ->where('product_id', $productId)
            ->when($variantId, fn($q) => $q->where('variant_id', $variantId))
            ->value('qty') ?? 0;
    }

    public function getLowStockItems(string $branchId): \Illuminate\Database\Eloquent\Collection
    {
        return Stock::with(['product', 'variant'])
            ->where('branch_id', $branchId)
            ->whereColumn('qty', '<=', 'min_qty')
            ->get();
    }
}
