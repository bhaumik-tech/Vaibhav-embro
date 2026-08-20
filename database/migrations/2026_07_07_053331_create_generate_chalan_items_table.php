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
        Schema::create('generate_chalan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generate_chalan_id')->constrained()->onDelete('cascade');
            $table->string('bundle')->nullable();
            $table->string('code')->nullable();
            $table->integer('pcs')->default(0);
            $table->decimal('rate', 10, 2)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generate_chalan_items');
    }
};
