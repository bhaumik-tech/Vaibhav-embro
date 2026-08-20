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
        Schema::create('inter_exchange_setups', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('type_of_box')->nullable();
            $table->string('box_cone')->nullable();
            $table->decimal('rate', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inter_exchange_setups');
    }
};
