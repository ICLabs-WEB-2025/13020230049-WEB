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
        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade'); // Relasi dengan transaksi
            $table->string('action_type'); // Jenis aksi yang dilakukan pada transaksi
            $table->text('description'); // Deskripsi log
            $table->timestamp('action_date')->useCurrent(); // Waktu aksi
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_logs');
    }
};
