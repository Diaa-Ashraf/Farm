<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('financial_record_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->date('payment_date');
            $table->string('payment_method')->nullable();
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['financial_record_id', 'payment_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_payments');
    }
};
