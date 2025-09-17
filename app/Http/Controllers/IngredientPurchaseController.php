<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\IngredientPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;
use App\Models\BalanceHistory;
use Exception;


class IngredientPurchaseController extends Controller
{
    /**
     * Menampilkan daftar riwayat pembelian dalam format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Eager loading tetap digunakan untuk performa optimal
        $purchases = IngredientPurchase::with(['ingredient:id,name,unit', 'user:id,name'])
                                       ->latest()
                                       ->paginate(15);

        // Mengembalikan response JSON standar
        return response()->json([
            'success' => true,
            'message' => 'Data riwayat pembelian berhasil diambil.',
            'data'    => $purchases
        ], 200); // HTTP 200 OK
    }

    /**
     * Menyimpan data pembelian baru dan mengembalikan data yang baru dibuat sebagai JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ingredient_id' => 'required|integer|exists:ingredients,id',
            'quantity_purchased' => 'required|numeric|min:0.01',
            'cost' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // --- TAMBAHAN: Pengecekan otentikasi pengguna ---
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak terautentikasi. Silakan login terlebih dahulu.',
            ], 401);
        }
        // --- AKHIR TAMBAHAN ---

        // Mulai transaksi database untuk memastikan semua operasi berhasil atau gagal bersamaan
        DB::beginTransaction();
        try {
            $ingredient = Ingredient::lockForUpdate()->findOrFail($request->ingredient_id);

            // 1. Simpan data pembelian
            IngredientPurchase::create([
                'ingredient_id' => $ingredient->id,
                'user_id' => Auth::id(), // Menggunakan Auth::id() yang sudah dicek
                'quantity_purchased' => $request->quantity_purchased,
                'cost' => $request->cost,
                'purchase_date' => $request->purchase_date,
            ]);

            // 2. Tambah stok bahan baku
            $ingredient->current_stock += $request->quantity_purchased;
            $ingredient->save();

            // 3. Kurangi saldo (total_revenue)
            $totalRevenueSetting = Setting::where('key', 'total_revenue')->lockForUpdate()->firstOrFail();
            $totalRevenueSetting->value -= $request->cost;
            $totalRevenueSetting->save();
            
            // 4. Catat di riwayat saldo
            BalanceHistory::create([
                'type' => 'expense',
                'amount' => $request->cost,
                'reason' => "Pembelian bahan baku: {$ingredient->name}",
            ]);

            // Jika semua berhasil, commit transaksi
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembelian berhasil dicatat dan saldo telah diperbarui.'
            ], 201);

        } catch (Exception $e) {
            // Jika ada error, batalkan semua perubahan
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pembelian.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}