<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained()->onDelete('cascade');
            $table->foreignId('worker_id')->constrained()->onDelete('cascade');
            $table->date('work_start_date');
            $table->date('work_end_date')->nullable();
            $table->decimal('daily_wage', 8, 2);
            $table->integer('actual_days')->nullable();
            $table->decimal('gross_wage', 10, 2)->nullable();
            $table->decimal('total_advances', 10, 2)->default(0);
            $table->decimal('net_wage', 10, 2)->nullable();
            $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid');
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('remaining', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['cycle_id', 'worker_id']);
            $table->index('cycle_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_cycles');
    }
};
