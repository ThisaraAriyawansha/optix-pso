<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 150);
            $table->string('phone', 20)->nullable()->unique();
            $table->string('email', 100)->nullable()->unique();
            $table->text('address')->nullable();
            $table->string('id_number', 50)->nullable();
            $table->integer('loyalty_points')->default(0);
            $table->decimal('credit_limit', 12, 2)->default(0.00);
            $table->decimal('credit_balance', 12, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
