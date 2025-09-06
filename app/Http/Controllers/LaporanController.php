<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function dashboardOverview(Request $request)
    {
        // Set tanggal hari ini dan kemarin untuk perbandingan
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // ## 1. CARD: Penjualan Hari Ini ##
        $salesToday = Transaction::whereDate('created_at', $today)->sum('total_price');
        $salesYesterday = Transaction::whereDate('created_at', $yesterday)->sum('total_price');
        
        $salesPercentageChange = 0;
        if ($salesYesterday > 0) {
            $salesPercentageChange = (($salesToday - $salesYesterday) / $salesYesterday) * 100;
        } elseif ($salesToday > 0) {
            $salesPercentageChange = 100; // Jika kemarin 0 dan hari ini ada penjualan
        }

        // ## 2. CARD: Total Transaksi ##
        $transactionCountToday = Transaction::whereDate('created_at', $today)->count();
        $transactionCountYesterday = Transaction::whereDate('created_at', $yesterday)->count();

        $transactionPercentageChange = 0;
        if ($transactionCountYesterday > 0) {
            $transactionPercentageChange = (($transactionCountToday - $transactionCountYesterday) / $transactionCountYesterday) * 100;
        } elseif ($transactionCountToday > 0) {
            $transactionPercentageChange = 100; // Jika kemarin 0 dan hari ini ada transaksi
        }

        // ## 3. CARD: Produk Terlaris Hari Ini ##
        $topProductToday = TransactionDetail::with('product:id,name')
            ->whereHas('transaction', function ($query) use ($today) {
                $query->whereDate('created_at', $today);
            })
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->first();

        // ## 4. GRAFIK: Penjualan Mingguan ##
        $weeklySalesLabels = [];
        $weeklySalesData = [];
        Carbon::setLocale('id'); // Set lokal ke bahasa Indonesia

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $sales = Transaction::whereDate('created_at', $date)->sum('total_price');
            
            $weeklySalesLabels[] = $date->isoFormat('ddd'); // Output: Kam, Jum, Sab, ...
            $weeklySalesData[] = (float) $sales;
        }

        // ## 5. GRAFIK: Produk Terlaris (Donut Chart) ##
        $topProductsForChart = TransactionDetail::with('product:id,name')
            ->whereHas('transaction', function ($query) use ($today) {
                $query->whereDate('created_at', $today);
            })
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(4) // Ambil 4 produk teratas
            ->get();

        $pieChartLabels = [];
        $pieChartData = [];
        $totalQuantityTopProducts = $topProductsForChart->sum('total_quantity');
        
        foreach ($topProductsForChart as $detail) {
            $pieChartLabels[] = $detail->product->name;
            $pieChartData[] = (int) $detail->total_quantity;
        }
        
        // Hitung total kuantitas semua produk yang terjual hari ini
        $totalQuantityAllProducts = TransactionDetail::whereHas('transaction', function ($query) use ($today) {
            $query->whereDate('created_at', $today);
        })->sum('quantity');
        
        // Hitung kuantitas "Lainnya"
        $otherProductsQuantity = $totalQuantityAllProducts - $totalQuantityTopProducts;

        if ($otherProductsQuantity > 0) {
            $pieChartLabels[] = 'Lainnya';
            $pieChartData[] = (int) $otherProductsQuantity;
        }
        
        // ## Susun semua data untuk respons JSON ##
        $data = [
            'overview' => [
                'sales_today' => [
                    'value' => (float) $salesToday,
                    'percentage_change' => round($salesPercentageChange, 2),
                ],
                'transactions_today' => [
                    'count' => (int) $transactionCountToday,
                    'percentage_change' => round($transactionPercentageChange, 2),
                ],
                'top_product_today' => $topProductToday ? [
                    'name' => $topProductToday->product->name,
                    'quantity_sold' => (int) $topProductToday->total_quantity,
                ] : null,
            ],
            'charts' => [
                'weekly_sales' => [
                    'labels' => $weeklySalesLabels,
                    'data' => $weeklySalesData,
                ],
                'top_products' => [
                    'labels' => $pieChartLabels,
                    'data' => $pieChartData,
                ],
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
   public function dailyReport(Request $request)
    {
        $request->validate(['date' => 'required|date_format:Y-m-d']);
        $date = Carbon::parse($request->input('date'));
        
        $query = Transaction::query()->whereDate('created_at', $date);
        $period = "Tanggal " . $date->isoFormat('D MMMM Y');

        return $this->generateResponse($query, 'daily', $period);
    }

    public function monthlyReport(Request $request)
    {
        // [MODIFIKASI] Disesuaikan untuk input type="month" (format: YYYY-MM)
        $request->validate(['month' => 'required|date_format:Y-m']);
        
        $monthData = Carbon::parse($request->input('month'));
        $year = $monthData->year;
        $month = $monthData->month;

        $query = Transaction::query()->whereYear('created_at', $year)->whereMonth('created_at', $month);
        $period = "Bulan " . $monthData->isoFormat('MMMM Y');

        return $this->generateResponse($query, 'monthly', $period);
    }

    public function yearlyReport(Request $request)
    {
        $request->validate(['year' => 'required|digits:4']);
        $year = $request->input('year');

        $query = Transaction::query()->whereYear('created_at', $year);
        $period = "Tahun " . $year;

        return $this->generateResponse($query, 'yearly', $period);
    }

    public function weeklyReport(Request $request)
    {
        // [MODIFIKASI] Disesuaikan untuk input type="week" (format: YYYY-Www)
        $request->validate(['week' => 'required|string']);
        
        // Parsing input seperti "2025-W36"
        list($year, $week) = sscanf($request->input('week'), '%d-W%d');

        $startDate = Carbon::now()->setISODate($year, $week)->startOfWeek();
        $endDate = Carbon::now()->setISODate($year, $week)->endOfWeek();

        $query = Transaction::query()->whereBetween('created_at', [$startDate, $endDate]);
        $period = "Minggu ke-$week, Tahun $year (" . $startDate->isoFormat('D MMM') . ' - ' . $endDate->isoFormat('D MMM Y') . ")";

        return $this->generateResponse($query, 'weekly', $period);
    }
    
    // Fungsi ini sekarang menjadi redundant jika sudah ada frontend dengan date picker.
    // Tapi jika tetap ingin digunakan, ini sudah benar.
    public function pickDayReport(Request $request)
    {
        $request->validate(['date' => 'nullable|date_format:Y-m-d']);
        $date = $request->filled('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        $query = Transaction::query()->whereDate('created_at', $date);
        $period = "Tanggal " . $date->isoFormat('D MMMM Y');
        return $this->generateResponse($query, 'daily', $period);
    }

    /**
     * [PERUBAHAN UTAMA] Fungsi ini sekarang melakukan agregasi di database
     * untuk laporan mingguan, bulanan, dan tahunan agar lebih efisien.
     */
    private function generateResponse($query, string $type, string $period)
    {
        $reportData = [];
        $header = "Tanggal"; // Default header

        switch ($type) {
            case 'daily':
                // Untuk harian, kita tampilkan transaksi individual
                $reportData = $query->with('transaction_details')
                                    ->select('id', 'customer_name','transaction_code', 'total_price', 'created_at')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                $header = "Kode Transaksi";
                break;

            case 'weekly':
            case 'monthly':
                // Untuk mingguan & bulanan, kita ringkas data per hari
                $reportData = $query->select(
                                        DB::raw('DATE(created_at) as period_label'),
                                        DB::raw('COUNT(id) as total_transactions'),
                                        DB::raw('SUM(total_price) as total_sales')
                                    )
                                    ->groupBy('period_label')
                                    ->orderBy('period_label', 'desc')
                                    ->get();
                $header = "Tanggal";
                break;

            case 'yearly':
                // Untuk tahunan, kita ringkas data per bulan
                $reportData = $query->select(
                                        DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period_label"),
                                        DB::raw('COUNT(id) as total_transactions'),
                                        DB::raw('SUM(total_price) as total_sales')
                                    )
                                    ->groupBy('period_label')
                                    ->orderBy('period_label', 'desc')
                                    ->get();
                $header = "Bulan";
                break;
        }

        return response()->json([
            'success' => true,
            'report' => [
                'type' => $type,
                'period' => $period,
                'header' => $header, // [BARU] Header dinamis untuk tabel
                'data' => $reportData, // [MODIFIKASI] Nama diubah menjadi 'data'
            ]
        ]);
    }
}
