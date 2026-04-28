<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id')->nullable();
            $table->uuid('branch_id');
            $table->uuid('created_by');
            $table->string('quote_number', 50)->unique();
            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected', 'converted', 'expired'])->default('draft');
            $table->date('valid_until')->nullable();
            $table->string('customer_name', 150)->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->string('customer_email', 100)->nullable();
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->enum('discount_type', ['fixed', 'percent'])->default('fixed');
            $table->decimal('discount_value', 12, 2)->default(0.00);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->decimal('total', 12, 2)->default(0.00);
            $table->text('terms_and_conditions')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('created_by')->references('id')->on('users');
        });

        Schema::create('quotation_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('quotation_id');
            $table->uuid('product_id')->nullable();
            $table->uuid('variant_id')->nullable();
            $table->string('description', 300);
            $table->integer('qty')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0.00);
            $table->decimal('discount_pct', 5, 2)->default(0.00);
            $table->decimal('line_total', 12, 2)->default(0.00);
            $table->integer('sort_order')->default(0);
            $table->foreign('quotation_id')->references('id')->on('quotations')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
            $table->foreign('variant_id')->references('id')->on('product_variants')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('quotations');
    }
};
