<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('miscellaneous_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->string('paid_to')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->enum('payment_status', ['paid', 'unpaid'])->default('paid');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('cycle_id');
            $table->index('expense_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('miscellaneous_expenses');
    }
};
