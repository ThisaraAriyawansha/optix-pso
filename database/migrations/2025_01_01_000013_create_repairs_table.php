<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repair_jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');
            $table->uuid('branch_id');
            $table->uuid('technician_id')->nullable();
            $table->uuid('created_by');
            $table->string('job_number', 50)->unique();
            $table->string('device_type', 100);
            $table->string('device_brand', 100)->nullable();
            $table->string('device_model', 100)->nullable();
            $table->string('serial_number', 200)->nullable();
            $table->text('reported_issue');
            $table->text('diagnosis')->nullable();
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', ['received', 'diagnosing', 'waiting_parts', 'in_repair', 'ready', 'delivered', 'cancelled'])->default('received');
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->decimal('final_cost', 12, 2)->nullable();
            $table->uuid('invoice_id')->nullable();
            $table->date('received_date');
            $table->date('promised_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('technician_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('invoice_id')->references('id')->on('invoices')->nullOnDelete();
        });

        Schema::create('repair_job_history', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('repair_job_id');
            $table->uuid('user_id');
            $table->string('from_status', 50)->nullable();
            $table->string('to_status', 50);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('repair_job_id')->references('id')->on('repair_jobs')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('repair_parts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('repair_job_id');
            $table->uuid('product_id')->nullable();
            $table->string('description', 300);
            $table->integer('qty')->default(1);
            $table->decimal('unit_cost', 12, 2)->default(0.00);
            $table->decimal('line_total', 12, 2)->default(0.00);
            $table->foreign('repair_job_id')->references('id')->on('repair_jobs')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_parts');
        Schema::dropIfExists('repair_job_history');
        Schema::dropIfExists('repair_jobs');
    }
};
