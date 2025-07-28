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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashbox_id')->constrained('cashboxes')->onDelete('cascade');
            $table->enum('type', ['income', 'expense']);
            $table->boolean('is_debt')->default(false);
            $table->string('article_type')->nullable();
            $table->string('article_description')->nullable();
            $table->decimal('original_amount', 18, 2);
            $table->string('original_currency');
            $table->decimal('amount', 18, 2); // in cashbox currency
            $table->string('currency'); // should match cashbox currency
            $table->decimal('exchange_rate', 15, 6);
            $table->date('date');
            $table->string('link')->nullable();
            $table->string('object')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
