<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cycle_closings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->unique()->constrained()->onDelete('cascade');
            $table->date('closing_date');
            $table->integer('initial_count');
            $table->integer('mortality_count');
            $table->integer('sold_count');
            $table->integer('cycle_days');
            $table->decimal('chick_cost', 10, 2)->default(0);
            $table->decimal('feed_cost', 10, 2)->default(0);
            $table->decimal('feed_tons', 10, 3)->default(0);
            $table->decimal('medicine_cost', 10, 2)->default(0);
            $table->decimal('gas_cost', 10, 2)->default(0);
            $table->decimal('sawdust_cost', 10, 2)->default(0);
            $table->decimal('sawdust_tons', 10, 3)->default(0);
            $table->decimal('worker_cost', 10, 2)->default(0);
            $table->decimal('misc_cost', 10, 2)->default(0);
            $table->decimal('total_expenses', 10, 2)->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0);
            $table->decimal('total_weight_kg', 10, 2)->default(0);
            $table->decimal('net_profit', 10, 2)->default(0);
            $table->enum('result', ['profit', 'loss', 'break_even'])->default('break_even');
            $table->decimal('fcr', 6, 3)->nullable();
            $table->decimal('avg_weight_kg', 6, 3)->nullable();
            $table->decimal('mortality_rate', 5, 2)->nullable();
            $table->decimal('profit_per_kg', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cycle_closings');
    }
};
