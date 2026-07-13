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
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('firm_id')->constrained()->cascadeOnDelete();
            $table->string('machine_no');
            $table->string('place')->nullable();
            $table->integer('no_of_head')->nullable();
            $table->string('area')->nullable();
            $table->string('top_dup')->nullable();
            $table->boolean('bonus_production_enabled')->default(false);
            $table->integer('bonus_production_value')->nullable();
            $table->boolean('bonus_frame_enabled')->default(false);
            $table->integer('bonus_frame_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
