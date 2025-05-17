<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi dengan pengguna
            $table->foreignId('category_id')->constrained('expense_categories')->onDelete('cascade'); // Relasi dengan kategori pengeluaran
            $table->decimal('amount', 10, 2);
            $table->enum('transaction_type', ['income', 'expense']);
            $table->text('description');
            $table->date('date'); // Menambahkan kolom date
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
