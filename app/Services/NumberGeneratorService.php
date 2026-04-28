<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\RepairJob;
use App\Models\PurchaseOrder;
use App\Models\BranchTransfer;

class NumberGeneratorService
{
    public function nextInvoiceNumber(): string
    {
        $prefix = 'INV';
        $year = date('Y');
        $last = Invoice::where('invoice_number', 'like', "{$prefix}-{$year}-%")
            ->orderByDesc('invoice_number')
            ->value('invoice_number');
        return $this->increment($prefix, $year, $last);
    }

    public function nextQuotationNumber(): string
    {
        $prefix = 'QUO';
        $year = date('Y');
        $last = Quotation::where('quote_number', 'like', "{$prefix}-{$year}-%")
            ->orderByDesc('quote_number')
            ->value('quote_number');
        return $this->increment($prefix, $year, $last);
    }

    public function nextRepairNumber(): string
    {
        $prefix = 'REP';
        $year = date('Y');
        $last = RepairJob::where('job_number', 'like', "{$prefix}-{$year}-%")
            ->orderByDesc('job_number')
            ->value('job_number');
        return $this->increment($prefix, $year, $last);
    }

    public function nextPoNumber(): string
    {
        $prefix = 'PO';
        $year = date('Y');
        $last = PurchaseOrder::where('po_number', 'like', "{$prefix}-{$year}-%")
            ->orderByDesc('po_number')
            ->value('po_number');
        return $this->increment($prefix, $year, $last);
    }

    public function nextTransferNumber(): string
    {
        $prefix = 'TRF';
        $year = date('Y');
        $last = BranchTransfer::where('transfer_number', 'like', "{$prefix}-{$year}-%")
            ->orderByDesc('transfer_number')
            ->value('transfer_number');
        return $this->increment($prefix, $year, $last);
    }

    private function increment(string $prefix, string $year, ?string $last): string
    {
        $seq = 1;
        if ($last) {
            $parts = explode('-', $last);
            $seq = (int) end($parts) + 1;
        }
        return sprintf('%s-%s-%05d', $prefix, $year, $seq);
    }
}
