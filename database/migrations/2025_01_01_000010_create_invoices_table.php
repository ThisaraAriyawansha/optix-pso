<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('quotation_id')->nullable();
            $table->uuid('customer_id')->nullable();
            $table->uuid('branch_id');
            $table->uuid('cashier_id');
            $table->string('invoice_number', 50)->unique();
            $table->enum('type', ['sale', 'service', 'purchase_return'])->default('sale');
            $table->enum('status', ['draft', 'issued', 'paid', 'partial', 'cancelled', 'refunded'])->default('issued');
            $table->string('customer_name', 150)->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->enum('discount_type', ['fixed', 'percent'])->default('fixed');
            $table->decimal('discount_value', 12, 2)->default(0.00);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->decimal('total', 12, 2)->default(0.00);
            $table->decimal('amount_paid', 12, 2)->default(0.00);
            $table->decimal('amount_due', 12, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('quotation_id')->references('id')->on('quotations')->nullOnDelete();
            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('cashier_id')->references('id')->on('users');
            $table->index('invoice_number');
            $table->index('status');
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('invoice_id');
            $table->uuid('product_id')->nullable();
            $table->uuid('variant_id')->nullable();
            $table->string('description', 300);
            $table->integer('qty')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0.00);
            $table->decimal('cost_price', 12, 2)->default(0.00);
            $table->decimal('discount_pct', 5, 2)->default(0.00);
            $table->decimal('line_total', 12, 2)->default(0.00);
            $table->string('serial_number', 200)->nullable();
            $table->integer('sort_order')->default(0);
            $table->foreign('invoice_id')->references('id')->on('invoices')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
            $table->foreign('variant_id')->references('id')->on('product_variants')->nullOnDelete();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('invoice_id');
            $table->uuid('cashier_id');
            $table->enum('method', ['cash', 'card', 'mobile_pay', 'bank_transfer', 'loyalty_points', 'credit']);
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->string('reference', 200)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->useCurrent();
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('cashier_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};
