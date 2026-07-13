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
        Schema::create('bank_books', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('firm_id')->constrained()->cascadeOnDelete();
            $table->foreignId('party_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['received', 'pay']); // 'received' = Credit, 'pay' = Debit
            $table->decimal('amount', 12, 2);
            $table->string('ref_no')->nullable(); // Cheque/RTGS/Bill no
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_books');
    }
};
