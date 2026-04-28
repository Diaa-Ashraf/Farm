<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sawdust_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained()->onDelete('cascade');
            $table->string('supplier_name')->nullable();
            $table->string('supplier_phone')->nullable();
            $table->decimal('quantity_tons', 8, 3);
            $table->decimal('price_per_ton', 10, 2);
            $table->decimal('total', 10, 2)->default(0);
            $table->date('purchase_date');
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('remaining', 10, 2)->default(0);
            $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('cycle_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sawdust_purchases');
    }
};
