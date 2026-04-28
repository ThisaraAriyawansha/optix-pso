<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\LoyaltyTransaction;
use Illuminate\Support\Facades\Auth;

class LoyaltyService
{
    private int $pointsPerUnit;

    public function __construct()
    {
        $this->pointsPerUnit = (int) (\App\Models\Setting::get('loyalty_points_per_unit', 100));
    }

    public function earn(Customer $customer, float $invoiceTotal, string $invoiceId): int
    {
        $points = (int) floor($invoiceTotal / $this->pointsPerUnit);
        if ($points <= 0) return 0;

        $customer->increment('loyalty_points', $points);

        LoyaltyTransaction::create([
            'customer_id' => $customer->id,
            'invoice_id' => $invoiceId,
            'user_id' => Auth::id(),
            'type' => 'earn',
            'points' => $points,
            'balance_after' => $customer->fresh()->loyalty_points,
        ]);

        return $points;
    }

    public function redeem(Customer $customer, int $points, string $invoiceId): bool
    {
        if ($customer->loyalty_points < $points) return false;

        $customer->decrement('loyalty_points', $points);

        LoyaltyTransaction::create([
            'customer_id' => $customer->id,
            'invoice_id' => $invoiceId,
            'user_id' => Auth::id(),
            'type' => 'redeem',
            'points' => -$points,
            'balance_after' => $customer->fresh()->loyalty_points,
        ]);

        return true;
    }

    public function adjust(Customer $customer, int $points, string $notes): void
    {
        $customer->increment('loyalty_points', $points);

        LoyaltyTransaction::create([
            'customer_id' => $customer->id,
            'user_id' => Auth::id(),
            'type' => 'adjust',
            'points' => $points,
            'balance_after' => $customer->fresh()->loyalty_points,
            'notes' => $notes,
        ]);
    }
}
