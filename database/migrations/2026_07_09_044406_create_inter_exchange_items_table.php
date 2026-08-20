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
        Schema::create('inter_exchange_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inter_exchange_id')->constrained()->cascadeOnDelete();
            $table->string('type_of_box')->nullable();
            $table->string('box_cone')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inter_exchange_items');
    }
};
