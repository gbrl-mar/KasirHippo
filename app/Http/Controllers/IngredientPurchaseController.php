<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\IngredientPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        // Validasi manual untuk kontrol response JSON yang lebih baik
        $validator = Validator::make($request->all(), [
            'ingredient_id'      => 'required|exists:ingredients,id',
            'quantity_purchased' => 'required|numeric|min:0.01',
            'cost'               => 'required|numeric|min:0',
            'purchase_date'      => 'required|date',
        ]);

        // Jika validasi gagal, kembalikan error dalam format JSON
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors()
            ], 422); // HTTP 422 Unprocessable Entity
        }

        try {
            $newPurchase = null; // Variabel untuk menampung data baru

            // Transaksi database tetap digunakan untuk keamanan data
            DB::transaction(function () use ($request, &$newPurchase) {
                $validatedData = $request->all();

                // 1. Update stok
                $ingredient = Ingredient::findOrFail($validatedData['ingredient_id']);
                $ingredient->increment('current_stock', $validatedData['quantity_purchased']);

                // 2. Buat record pembelian baru
                $newPurchase = IngredientPurchase::create([
                    'ingredient_id'      => $validatedData['ingredient_id'],
                    'quantity_purchased' => $validatedData['quantity_purchased'],
                    'cost'               => $validatedData['cost'],
                    'purchase_date'      => $validatedData['purchase_date'],
                    'user_id'            => Auth::id() ?? 1, // Ganti '1' dengan fallback user jika API tidak terautentikasi
                ]);
            });

            // Jika transaksi berhasil, kembalikan data baru
            return response()->json([
                'success' => true,
                'message' => 'Pembelian berhasil dicatat dan stok telah diperbarui!',
                'data'    => $newPurchase->load(['ingredient:id,name', 'user:id,name']) // Muat relasi untuk response
            ], 201); // HTTP 201 Created

        } catch (\Exception $e) {
            // Jika ada error server, kembalikan response error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.',
                'error'   => $e->getMessage()
            ], 500); // HTTP 500 Internal Server Error
        }
    }
}