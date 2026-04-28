<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gas_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gas_invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('gas_type_id')->constrained()->onDelete('restrict');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();
            $table->index('gas_invoice_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gas_invoice_items');
    }
};
