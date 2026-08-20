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
        Schema::create('thread_box_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_box_id')->constrained()->onDelete('cascade');
            $table->string('type_of_box');
            $table->string('box_cone');
            $table->decimal('quantity', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thread_box_items');
    }
};
