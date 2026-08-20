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
        Schema::create('rcvd_payments', function (Blueprint $table) {
            $table->id();
            $table->string('cheque_no')->nullable();
            $table->string('payment_type')->default('RTGS'); // RTGS, Cheque
            $table->date('date');
            $table->foreignId('party_id')->constrained()->cascadeOnDelete();
            $table->foreignId('firm_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->string('bill_month')->nullable();
            $table->string('bill_no')->nullable();
            $table->string('cheque_photo')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rcvd_payments');
    }
};
