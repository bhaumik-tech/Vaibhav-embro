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
        Schema::create('inter_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('firm_aapnar_id')->constrained('firms')->cascadeOnDelete();
            $table->foreignId('firm_lenar_id')->constrained('firms')->cascadeOnDelete();
            $table->string('chalan_no')->nullable();
            $table->date('date');
            $table->string('photo_path')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inter_exchanges');
    }
};
