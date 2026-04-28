<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mortality_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained()->onDelete('cascade');
            $table->date('log_date');
            $table->integer('count');
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->unique(['cycle_id', 'log_date']);
            $table->index(['cycle_id', 'log_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mortality_logs');
    }
};
