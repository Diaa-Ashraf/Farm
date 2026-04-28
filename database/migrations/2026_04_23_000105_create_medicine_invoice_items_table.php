<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicine_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_invoice_id')->constrained()->onDelete('cascade');
            $table->string('medicine_name');
            $table->string('unit')->default('علبة');
            $table->decimal('quantity', 8, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();
            $table->index('medicine_invoice_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_invoice_items');
    }
};
