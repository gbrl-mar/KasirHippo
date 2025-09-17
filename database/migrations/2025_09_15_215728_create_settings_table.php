<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Kunci pengaturan, cth: 'total_revenue'
            $table->text('value');          // Nilai dari pengaturan
            $table->timestamps();
        });

        // --- PERUBAHAN ---
        // Hitung total pendapatan dari semua transaksi yang sudah ada.
        // Pastikan tabel 'transactions' sudah ada sebelum migrasi ini dijalankan.
        $initialRevenue = DB::table('transactions')->sum('total_price');

        // Isi dengan nilai awal berdasarkan total transaksi yang ada.
        DB::table('settings')->insert([
            'key' => 'total_revenue',
            'value' => $initialRevenue,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};

