<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');
            $table->uuid('invoice_id')->nullable();
            $table->uuid('user_id');
            $table->enum('type', ['earn', 'redeem', 'adjust', 'expire'])->default('earn');
            $table->integer('points');
            $table->integer('balance_after');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('invoice_id')->references('id')->on('invoices')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 50)->unique();
            $table->enum('type', ['fixed', 'percent'])->default('fixed');
            $table->decimal('value', 12, 2)->default(0.00);
            $table->decimal('min_order_amount', 12, 2)->default(0.00);
            $table->integer('max_uses')->nullable();
            $table->integer('used_count')->default(0);
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('warranties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');
            $table->uuid('invoice_item_id');
            $table->uuid('product_id');
            $table->string('serial_number', 200)->nullable();
            $table->enum('warranty_type', ['seller', 'manufacturer'])->default('seller');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration_months')->default(12);
            $table->text('terms')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('invoice_item_id')->references('id')->on('invoice_items');
            $table->foreign('product_id')->references('id')->on('products');
            $table->index('serial_number');
            $table->index('end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranties');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('loyalty_transactions');
    }
};
