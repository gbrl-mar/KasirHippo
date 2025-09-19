<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\BalanceHistory;
use Exception;

class RevenueController extends Controller
{
    /**
     * Menampilkan total pendapatan saat ini.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return $this->show();
    }
    public function show()
    {
        $totalRevenue = Setting::where('key', 'total_revenue')->first();

        return response()->json([
            'success' => true,
            'message' => 'Total pendapatan berhasil diambil.',
            'data' => [
                'total_revenue' => (float) $totalRevenue->value,
            ]
        ]);
    }

    /**
     * Menambahkan nilai ke total pendapatan secara manual.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'reason' => 'required|string|max:255', // Alasan penambahan, cth: "Input penjualan lama"
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $totalRevenueSetting = Setting::where('key', 'total_revenue')->lockForUpdate()->first();
            BalanceHistory::create([
                'amount' => $request->amount,
                'type' => 'income',
                'reason' => $request->reason,
                'recorded_at' => now(),
            ]);
            $totalRevenueSetting->value += $request->amount;
            $totalRevenueSetting->save();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pendapatan berhasil ditambahkan secara manual.',
                'data' => [
                    'new_total_revenue' => (float) $totalRevenueSetting->value,
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menambahkan pendapatan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function tarikSaldo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $totalRevenueSetting = Setting::where('key', 'total_revenue')->lockForUpdate()->firstOrFail();
            
            
            if ($totalRevenueSetting->value < $request->amount) {
                
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menarik saldo. Saldo tidak mencukupi.'
                ], 400);
            }

        
            $totalRevenueSetting->value -= $request->amount;
            $totalRevenueSetting->save();
            
         
            BalanceHistory::create([
                'amount' => $request->amount,
                'type'   => 'expense', 
                'reason' => $request->reason,
                
            ]);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Saldo berhasil ditarik.',
                'data' => [
                    'new_total_revenue' => (float) $totalRevenueSetting->value,
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menarik saldo.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function recordExpense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $totalRevenueSetting = Setting::where('key', 'total_revenue')->lockForUpdate()->first();
            
            // Kurangi saldo dengan jumlah pengeluaran
            $totalRevenueSetting->value -= $request->amount;
            $totalRevenueSetting->save();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengeluaran berhasil dicatat dan saldo telah diperbarui.',
                'data' => [
                    'new_total_revenue' => (float) $totalRevenueSetting->value,
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mencatat pengeluaran.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

     public function history()
    {
        try {
            
            $histories = BalanceHistory::latest()->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $histories
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil riwayat saldo.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

