<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dhaga_cutting_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dhaga_cutting_id')->constrained()->onDelete('cascade');
            $table->string('rate_label'); // "0.50", "0.75", ..., "Custom"
            $table->decimal('rate_value', 8, 2)->default(0);
            $table->decimal('pieces', 12, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dhaga_cutting_items');
    }
};
