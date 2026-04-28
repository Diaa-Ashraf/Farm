<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('initial_count');
            $table->decimal('chick_price', 8, 2)->default(0);
            $table->decimal('chick_total', 10, 2)->default(0);
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['farm_id', 'status']);
            $table->index('start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cycles');
    }
};
