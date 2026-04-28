<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->nullable()->constrained()->nullOnDelete();
            $table->date('sale_date');
            $table->string('truck_number')->nullable();
            $table->decimal('total_weight_kg', 10, 2);
            $table->integer('bird_count')->nullable();
            $table->decimal('price_per_kg', 8, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('remaining', 10, 2)->default(0);
            $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['cycle_id', 'sale_date']);
            $table->index('buyer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
