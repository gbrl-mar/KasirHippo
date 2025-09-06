<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\TransactionResource;
use Exception;

class TransactionController extends Controller
{
    
    public function index()
    {
        // Mengambil semua transaksi dengan relasi detail dan produknya
        // Diurutkan berdasarkan yang terbaru dan menggunakan pagination
        $transactions = Transaction::with('transaction_details.product', 'user')
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Daftar transaksi berhasil diambil.',
            'data' => $transactions
        ]);
    }


    public function store(Request $request)
    {
        // Validasi input dari request
        $validator = Validator::make($request->all(), [
            'customer_name' => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:cash,credit_card,qris',
            'amount_paid' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'nullable|numeric|min:0', // Harga manual bersifat opsional
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $totalPrice = 0;
            $transactionDetailsData = [];

            // Mengambil semua ID produk dari request untuk di-query sekaligus
            $productIds = array_column($request->products, 'product_id');
            // Mengambil data produk dari database dan mengindeksnya berdasarkan ID untuk akses cepat
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($request->products as $item) {
                $product = $products->get($item['product_id']);

                // Penanganan jika produk (karena suatu hal) tidak ditemukan setelah query
                if (!$product) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => "Produk dengan ID {$item['product_id']} tidak ditemukan."], 404);
                }
                
                // Cek ketersediaan produk
                if (!$product->is_available) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Produk '{$product->name}' saat ini tidak tersedia."
                    ], 400);
                }
                
                // === LOGIKA HARGA DINAMIS ===
                $priceAtTransaction = $product->price; // Harga default dari database
                
                // Asumsi ID kategori Manual Brew adalah 7
                $manualBrewCategoryId = 7; 

                // Jika produk adalah Manual Brew DAN ada harga manual yang dikirim dari client
                if ($product->category_id == $manualBrewCategoryId && isset($item['price'])) {
                    // Gunakan harga yang diinput manual
                    $priceAtTransaction = $item['price'];
                }
                // === AKHIR LOGIKA HARGA DINAMIS ===
                
                $subtotal = $priceAtTransaction * $item['quantity'];
                $totalPrice += $subtotal;

                // Siapkan data untuk tabel transaction_details
                $transactionDetailsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price_at_transaction' => $priceAtTransaction, // Simpan harga yang digunakan saat transaksi
                    'subtotal' => $subtotal,
                ];
            }
            
            // Hitung kembalian
            $changeDue = $request->amount_paid - $totalPrice;
            if ($changeDue < 0) {
                 DB::rollBack();
                 return response()->json([
                     'success' => false,
                     'message' => 'Jumlah yang dibayarkan kurang dari total harga.'
                 ], 400);
            }

            // Buat record transaksi utama
            $transaction = Transaction::create([
                'user_id' => Auth::id(), // Mengambil ID user yang sedang login
                'transaction_code' => 'INV-' . time() . Auth::id(), // Contoh kode transaksi unik
                'customer_name' => $request->customer_name,
                'total_price' => $totalPrice,
                'payment_method' => $request->payment_method,
                'amount_paid' => $request->amount_paid,
                'change_due' => $changeDue,
                'payment_status' => 'paid', // Asumsi langsung lunas
                'transaction_date' => now(),
            ]);

            // Buat record detail transaksi menggunakan relasi
            $transaction->transaction_details()->createMany($transactionDetailsData);

            // Jika semua berhasil, commit transaksi ke database
            DB::commit();

            // Load relasi untuk data response JSON
            $transaction->load('transaction_details.product', 'user');
            
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat.',
                'data' => $transaction
            ], 201);

        } catch (Exception $e) {
            // Jika terjadi error, batalkan semua query yang sudah dijalankan
            DB::rollBack();

            // Beri response error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Transaction $transaction)
    {
        // Load relasi yang dibutuhkan sebelum mengirim response
        $transaction->load('transaction_details.product', 'user');

        return response()->json([
            'success' => true,
            'message' => 'Detail transaksi berhasil diambil.',
            'data' => $transaction
        ]);
    }

    public function getPaymentInfo(Request $request)
    {
        // 1. Ambil path gambar dari database
        $qrisPath = SiteSetting::where('setting_name', 'qris_image_url')->firstOrFail()->setting_value;

        // 2. Gunakan helper asset() untuk membuat URL lengkap
        $qrisUrl = Storage::url($qrisPath);

        return response()->json([
            'qris_image_url' => $qrisUrl
        ]);
    }

    public function nota(Transaction $transaction)
    {
        try {
            // Eager load relasi transaction_details beserta produk di dalamnya
            $transactionData = $transaction->load('transaction_details.product');

            return response()->json([
                'success' => true,
                'message' => 'Data nota berhasil diambil',
                'data'    => $transactionData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan atau terjadi kesalahan'
            ], 404);
        }
    } 

   public function history(Request $request)
{
    $query = Transaction::query()
        // Eager load relasi untuk menghindari N+1 Query Problem
        // Kita butuh 'user' dan 'transaction_details' beserta 'product' di dalamnya
        ->with(['user', 'transaction_details.product'])
        
        // ✅ Logika penting: History hanya untuk transaksi yang sudah selesai (final)
        ->whereIn('payment_status', ['paid', 'cancelled']);

    // Filter berdasarkan tanggal jika diberikan
    if ($request->has('date')) {
        // Sebaiknya divalidasi, tapi untuk sekarang kita sederhanakan
        $query->whereDate('transaction_date', $request->input('date'));
    }

    // Filter berdasarkan metode pembayaran jika diberikan
    if ($request->has('payment_method')) {
        $query->where('payment_method', $request->input('payment_method'));
    }

    // Mengurutkan dari yang terbaru dan melakukan paginasi
    $transactions = $query->latest('transaction_date')->paginate(15);

    // 🚀 Menggunakan Resource untuk mengubah koleksi data menjadi JSON yang rapi
    // Struktur paginasi (meta, links) akan otomatis ditambahkan oleh Laravel
    return TransactionResource::collection($transactions)->additional([
        'success' => true,
        'message' => 'Riwayat transaksi berhasil diambil.',
    ]);
}
public function adjustTransaction(Request $request, Transaction $transaction)
{
    // 1. Validasi Input
    // Pastikan data yang dikirim dari frontend sesuai dengan yang kita butuhkan
    $validator = Validator::make($request->all(), [
        'reason'       => 'required|string|max:255',
        'notes'        => 'nullable|string',
        'new_products' => 'required|array|min:1',
        'new_products.*.product_id' => 'required|exists:products,id',
        'new_products.*.quantity'   => 'required|integer|min:1',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    // 2. Cek Kondisi
    // Pastikan hanya transaksi yang statusnya 'paid' yang bisa diubah
    if ($transaction->payment_status !== 'paid') {
        return response()->json([
            'success' => false, 
            'message' => 'Gagal: Hanya transaksi yang sudah lunas yang bisa dikoreksi.'
        ], 400); // Bad Request
    }

    // 3. Proses Inti di dalam Database Transaction
    // Ini memastikan jika ada satu langkah gagal, semua perubahan akan dibatalkan (rollback)
    try {
        $updatedTransaction = DB::transaction(function () use ($request, $transaction) {
            // Langkah A: Hitung total harga baru dan selisihnya
            $newTotalPrice = 0;
            foreach ($request->new_products as $item) {
                $product = Product::find($item['product_id']);
                $newTotalPrice += $product->price * $item['quantity'];
            }
            $amountChange = $newTotalPrice - $transaction->total_price;

            // Langkah B: Buat catatan di tabel 'transaction_adjustments'
            $transaction->adjustments()->create([
                'user_id'       => Auth::id(), // ID kasir yang login
                'reason'        => $request->reason,
                'amount_change' => $amountChange,
                'notes'         => $request->notes,
            ]);
            
            // Langkah C: Hapus detail transaksi lama dan buat yang baru
            $transaction->transaction_details()->delete();
            foreach ($request->new_products as $item) {
                $product = Product::find($item['product_id']);
                $transaction->transaction_details()->create([
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $product->price,
                ]);
            }

            // Langkah D: Update tabel 'transactions' utama
            $transaction->total_price = $newTotalPrice;
            $transaction->amount_paid += $amountChange; // Asumsi selisihnya langsung dibayar/dikembalikan
            // Anda bisa tambahkan logika untuk `change_due` jika perlu
            $transaction->save();
            
            return $transaction; // Kembalikan objek transaksi yang sudah diupdate
        });

        // 4. Berikan Respons Sukses
        // Kita load relasi terbaru dan bungkus dengan TransactionResource
        $finalData = $updatedTransaction->load(['user', 'transaction_details.product', 'adjustments.user']);

        return (new TransactionResource($finalData))->additional([
            'success' => true,
            'message' => 'Transaksi berhasil dikoreksi.',
        ]);

    } catch (\Exception $e) {
        // Jika terjadi error di dalam DB::transaction
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan server saat memproses koreksi.',
            'error'   => $e->getMessage() // Hanya untuk development
        ], 500);
    }}
}

