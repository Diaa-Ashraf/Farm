<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feed_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained()->onDelete('cascade');
            $table->foreignId('feed_type_id')->constrained('feed_types')->onDelete('restrict');
            $table->string('supplier_name')->nullable();
            $table->string('supplier_phone')->nullable();
            $table->enum('unit', ['ton', 'bag'])->default('ton');
            $table->decimal('quantity', 10, 3);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2)->default(0);
            $table->date('purchase_date');
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('remaining', 10, 2)->default(0);
            $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['cycle_id', 'feed_type_id']);
            $table->index('purchase_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_purchases');
    }
};
