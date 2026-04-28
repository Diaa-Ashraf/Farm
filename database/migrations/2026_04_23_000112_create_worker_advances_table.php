<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_advances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_cycle_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('advance_date');
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['worker_cycle_id', 'advance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_advances');
    }
};
