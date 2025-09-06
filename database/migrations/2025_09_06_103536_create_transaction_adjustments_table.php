<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionAdjustmentsTable extends Migration
{
    public function up()
    {
        Schema::create('transaction_adjustments', function (Blueprint $table) {
            $table->id();

            // Menghubungkan ke transaksi asli
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');

            // Siapa yang melakukan perubahan (penting untuk akuntabilitas)
            $table->foreignId('user_id')->comment('ID kasir/staf yang melakukan perubahan')->constrained('users');

            // Alasan perubahan
            $table->string('reason');

            // Perubahan nominal (bisa positif/negatif)
            $table->decimal('amount_change', 10, 2)->comment('Positif jika ada tambahan bayar, negatif jika ada refund');
            // Catatan tambahan jika perlu
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_adjustments');
    }
}