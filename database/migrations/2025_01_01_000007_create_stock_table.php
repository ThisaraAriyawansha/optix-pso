<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->uuid('product_id');
            $table->uuid('variant_id')->nullable();
            $table->integer('qty')->default(0);
            $table->integer('min_qty')->default(5);
            $table->unique(['branch_id', 'product_id', 'variant_id']);
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('variant_id')->references('id')->on('product_variants')->cascadeOnDelete();
        });

        Schema::create('serial_numbers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->uuid('variant_id')->nullable();
            $table->uuid('branch_id');
            $table->string('serial_number', 200)->unique();
            $table->enum('status', ['in_stock', 'sold', 'in_repair', 'returned', 'scrapped'])->default('in_stock');
            $table->date('purchased_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('variant_id')->references('id')->on('product_variants')->nullOnDelete();
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->index('serial_number');
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->uuid('product_id');
            $table->uuid('variant_id')->nullable();
            $table->uuid('user_id');
            $table->enum('type', ['purchase', 'sale', 'adjustment', 'transfer_in', 'transfer_out', 'return', 'damage'])->default('adjustment');
            $table->integer('qty_before');
            $table->integer('qty_change');
            $table->integer('qty_after');
            $table->string('reference', 100)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('variant_id')->references('id')->on('product_variants')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('serial_numbers');
        Schema::dropIfExists('stock');
    }
};
