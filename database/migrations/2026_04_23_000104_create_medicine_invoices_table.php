<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicine_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained()->onDelete('cascade');
            $table->string('pharmacy_name')->nullable();
            $table->string('pharmacy_phone')->nullable();
            $table->date('invoice_date');
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('remaining', 10, 2)->default(0);
            $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['cycle_id', 'invoice_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_invoices');
    }
};
