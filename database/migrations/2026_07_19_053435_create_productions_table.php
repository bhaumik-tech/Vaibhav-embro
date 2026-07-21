<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karigar_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('remark')->nullable();
            $table->boolean('is_highlight')->default(false);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('total_bonus', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
