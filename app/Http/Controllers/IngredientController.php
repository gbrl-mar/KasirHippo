<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class IngredientController extends Controller
{
    /**
     * Menampilkan daftar semua bahan (ingredients) dalam format JSON.
     */
    public function index()
    {
        $ingredients = Ingredient::latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Data bahan berhasil diambil.',
            'data'    => $ingredients
        ], 200);
    }

    /**
     * Menyimpan bahan baru ke database.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255|unique:ingredients,name',
            'unit'          => 'required|string|max:50',
            'current_stock' => 'sometimes|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $ingredient = Ingredient::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Bahan berhasil ditambahkan!',
            'data'    => $ingredient
        ], 201);
    }

    /**
     * Menampilkan detail satu bahan.
     */
    public function show(Ingredient $ingredient)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail bahan berhasil diambil.',
            'data'    => $ingredient
        ], 200);
    }

    /**
     * Memperbarui data bahan.
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        $validator = Validator::make($request->all(), [
            'name'          => [
                'required',
                'string',
                'max:255',
                Rule::unique('ingredients')->ignore($ingredient->id),
            ],
            'unit'          => 'required|string|max:50',
            'current_stock' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $ingredient->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Bahan berhasil diperbarui!',
            'data'    => $ingredient
        ], 200);
    }

    /**
     * Menghapus bahan.
     */
    public function destroy(Ingredient $ingredient)
    {
        // Opsional: cek relasi pembelian
        if (method_exists($ingredient, 'purchases') && $ingredient->purchases()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus. Bahan ini memiliki riwayat pembelian.'
            ], 409);
        }

        $ingredient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bahan berhasil dihapus!'
        ], 200);
    }
}
