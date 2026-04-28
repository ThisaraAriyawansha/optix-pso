<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_transfers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('from_branch_id');
            $table->uuid('to_branch_id');
            $table->uuid('requested_by');
            $table->uuid('approved_by')->nullable();
            $table->string('transfer_number', 50)->unique();
            $table->enum('status', ['pending', 'approved', 'in_transit', 'received', 'cancelled'])->default('pending');
            $table->date('requested_date');
            $table->date('received_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('from_branch_id')->references('id')->on('branches');
            $table->foreign('to_branch_id')->references('id')->on('branches');
            $table->foreign('requested_by')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('branch_transfer_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('transfer_id');
            $table->uuid('product_id');
            $table->uuid('variant_id')->nullable();
            $table->integer('qty_requested');
            $table->integer('qty_received')->default(0);
            $table->foreign('transfer_id')->references('id')->on('branch_transfers')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('variant_id')->references('id')->on('product_variants')->nullOnDelete();
        });

        Schema::create('expense_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->uuid('user_id');
            $table->uuid('category_id')->nullable();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->string('reference', 200)->nullable();
            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('expense_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('branch_transfer_items');
        Schema::dropIfExists('branch_transfers');
    }
};
