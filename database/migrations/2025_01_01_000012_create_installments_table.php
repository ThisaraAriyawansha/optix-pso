<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');
            $table->uuid('invoice_id');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('down_payment', 12, 2)->default(0.00);
            $table->decimal('amount_paid', 12, 2)->default(0.00);
            $table->decimal('balance', 12, 2);
            $table->integer('installment_count');
            $table->decimal('installment_amount', 12, 2);
            $table->date('start_date');
            $table->date('next_due_date')->nullable();
            $table->enum('status', ['active', 'completed', 'overdue', 'defaulted'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('invoice_id')->references('id')->on('invoices');
        });

        Schema::create('installment_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('installment_id');
            $table->uuid('cashier_id');
            $table->decimal('amount', 12, 2);
            $table->enum('method', ['cash', 'card', 'mobile_pay', 'bank_transfer'])->default('cash');
            $table->string('reference', 200)->nullable();
            $table->date('paid_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('installment_id')->references('id')->on('installments');
            $table->foreign('cashier_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installment_payments');
        Schema::dropIfExists('installments');
    }
};
