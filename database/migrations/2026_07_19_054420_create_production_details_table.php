<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained()->cascadeOnDelete();
            $table->foreignId('machine_id')->nullable()->constrained()->nullOnDelete();
            $table->string('machine_index')->nullable(); 
            $table->boolean('is_active')->default(true);
            $table->boolean('is_half')->default(false);
            $table->foreignId('second_karigar_id')->nullable()->constrained('karigars')->nullOnDelete();
            $table->string('holiday_reason')->nullable();
            $table->string('mate_type')->nullable(); 
            
            $table->decimal('top_production', 10, 2)->nullable();
            $table->decimal('top_amount', 10, 2)->nullable();
            $table->decimal('top_bonus', 10, 2)->nullable();
            
            $table->decimal('dup_pro_frame_1', 10, 2)->nullable();
            $table->decimal('dup_bonus_frame_1', 10, 2)->nullable();
            $table->decimal('dup_kam_1', 10, 2)->nullable();
            
            $table->decimal('dup_pro_frame_2', 10, 2)->nullable();
            $table->decimal('dup_bonus_frame_2', 10, 2)->nullable();
            $table->decimal('dup_kam_2', 10, 2)->nullable();
            
            $table->decimal('dup_pro_frame_3', 10, 2)->nullable();
            $table->decimal('dup_bonus_frame_3', 10, 2)->nullable();
            $table->decimal('dup_kam_3', 10, 2)->nullable();
            
            $table->decimal('dup_total_pct', 10, 2)->nullable();
            $table->decimal('dup_amount', 10, 2)->nullable();
            $table->decimal('dup_bonus', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_details');
    }
};
