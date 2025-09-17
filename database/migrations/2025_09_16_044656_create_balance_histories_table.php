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
        Schema::create('balance_histories', function (Blueprint $table) {
            $table->id();
            // Tipe transaksi: pemasukan dari penjualan, pengeluaran, atau penambahan manual
            $table->enum('type', ['income', 'expense', 'manual_add']);
            $table->decimal('amount', 15, 2); // Jumlah uang yang terlibat
            $table->string('reason'); // Keterangan/alasan transaksi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_histories');
    }
};