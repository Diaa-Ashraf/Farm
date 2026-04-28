<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->nullable()->constrained()->nullOnDelete();
            $table->string('party_name');
            $table->string('party_phone')->nullable();
            $table->string('party_type')->nullable();
            $table->enum('type', ['payable', 'receivable']);
            $table->decimal('original_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('remaining', 10, 2);
            $table->date('due_date')->nullable();
            $table->enum('status', ['active', 'settled'])->default('active');
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index('cycle_id');
            $table->index('due_date');
            $table->index(['source_type', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_records');
    }
};
